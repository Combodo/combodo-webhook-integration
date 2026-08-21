<?php

//
// iTop module definition file
//
//
//
SetupWebPage::AddModule(
	__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'combodo-webhook-integration/1.4.8',
	[
		//
		'label' => 'Webhook integrations',
		'category' => 'business',

		// Setup
		//
		'dependencies' => [
			'itop-structure/3.2.0',
			'itop-attribute-encrypted-password/1.0.0',
			'combodo-oauth2-client/1.0.0',
		],
		'mandatory' => false,
		'visible' => true,
		'installer' => 'WebhookIntegrationInstaller',

		// Components
		//
		'datamodel' => [
			// Extension autoloader
			'vendor/autoload.php',
			// Explicitly load DM classes
			'SendWebRequest.php',
			'model.combodo-webhook-integration.php',
		],
		'webservice' => [],
		'data.struct' => [
			'data.struct.remote_application_type.xml',
		],
		'data.sample' => [],

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any

		// Default settings
		//
		'settings' => [],
	]
);

if (!class_exists('WebhookIntegrationInstaller')) {
	// Module installation handler
	//
	class WebhookIntegrationInstaller extends ModuleInstallerAPI
	{
		/**
		 * @inheritDoc
		 * @since 1.4.0
		 */
		public static function BeforeDatabaseCreation(Config $oConfiguration, $sPreviousVersion, $sCurrentVersion)
		{
			if (strlen($sPreviousVersion) === 0) {
				return;
			}

			// Search for existing ActionWebhook where the language attribute was defined on its child
			if (version_compare($sPreviousVersion, '1.4.0', '<')) {
				SetupLog::Info("|  Migrate ActionWebhook language attribute values to its parent.");
				$sTableToRead = MetaModel::DBGetTable('ActionWebhook');
				$sTableToSet = MetaModel::DBGetTable('ActionNotification');
				self::MoveColumnInDB($sTableToRead, 'language', $sTableToSet, 'language', true);
				SetupLog::Info("|  ActionWebhook migration done.");
			}

			// In combodo-webhook-integration v1.4.2, bug N°7875 led to token format change
			if (version_compare($sPreviousVersion, '1.4.2', '<')) {
				SetupLog::Info("|  Migrate RemoteiTopConnectionToken token format.");
				self::MigrateTokenFromEncryptedBlobToString();
				SetupLog::Info("|  RemoteiTopConnectionToken migration done.");
			}
		}

		/**
		 * Convert token encrypted binary format into clear token strings to prevent new column format from rejecting the data.
		 *
		 * @throws Exception When at least one token cannot be migrated safely.
		 */
		private static function MigrateTokenFromEncryptedBlobToString()
		{
			if (!MetaModel::DBExists(false)) {
				return;
			}

			// Note: There is an issue when upgrading, encryption values cannot be retrieved from the passed configuration, we have to read it from the disk
			$oDiskConfig = utils::GetConfig(true);

			$sClass = 'RemoteiTopConnectionToken';
			$sTable = MetaModel::DBGetTable($sClass);
			$sColumn = MetaModel::GetAttributeDef($sClass, 'token')->Get('sql');

			if (!CMDBSource::IsTable($sTable) || !CMDBSource::IsField($sTable, $sColumn)) {
				SetupLog::Info("|  Token migration skipped: table/column not found.");
				return;
			}

			$sFieldType = strtolower((string) CMDBSource::GetFieldType($sTable, $sColumn));
			// Check if we're dealing with a *blob (tinyblob is expected from the AttributeEncryptedString definition)
			if (strpos($sFieldType, 'blob') === false) {
				SetupLog::Info("|  Token migration skipped: column already non-binary ({$sFieldType}).");
				return;
			}

			SetupLog::Info("|  Migrating encrypted token payloads from binary column {$sTable}.{$sColumn}.");
			$aRows = CMDBSource::QueryToArray("SELECT `id`, `{$sColumn}` FROM `{$sTable}` WHERE `{$sColumn}` IS NOT NULL AND LENGTH(`{$sColumn}`) > 0");
			if (empty($aRows)) {
				SetupLog::Info("|  Token migration skipped: no non-empty values found.");
				return;
			}

			// Load SimpleCrypt object to decrypt values
			$oSimpleCrypt = new SimpleCrypt($oDiskConfig->GetEncryptionLibrary());
			$sEncryptionKey = $oDiskConfig->GetEncryptionKey();

			$sDecryptError = Dict::S('Core:AttributeEncryptFailedToDecrypt');
			$iMigrated = 0;
			$aUpdatedValues = [];
			$aFailedIds = [];

			foreach ($aRows as $aRow) {
				$iId = (int) $aRow['id'];
				$sEncryptedValue = $aRow[$sColumn];

				// Try to decrypt token raw value
				try {
					$sDecryptedValue = $oSimpleCrypt->Decrypt($sEncryptionKey, $sEncryptedValue);
				} catch (Exception $e) {
					$aFailedIds[] = $iId;
					SetupLog::Error("|  Token migration failed for id={$iId}: {$e->getMessage()}");
					continue;
				}

				// If the decrypted value equals common error message, consider something went wrong and skip this line
				if ($sDecryptedValue === $sDecryptError) {
					$aFailedIds[] = $iId;
					SetupLog::Error("|  Token migration failed for id={$iId}: decryption error.");
					continue;
				}

				$aUpdatedValues[$iId] = $sDecryptedValue;
			}

			// Throw an exception before trying to go forward with the database if we couldn't decipher all values, avoiding MySQL errors
			if (!empty($aFailedIds)) {
				throw new Exception('|  Token migration aborted: unable to decrypt token values for ids '.implode(', ', $aFailedIds).'.');
			}

			CMDBSource::Query('START TRANSACTION');
			try {
				foreach ($aUpdatedValues as $iId => $sDecryptedValue) {
					CMDBSource::Query(
						"UPDATE `{$sTable}` SET `{$sColumn}` = ".CMDBSource::Quote($sDecryptedValue)." WHERE `id` = {$iId}"
					);
					$iMigrated++;
				}
				CMDBSource::Query('COMMIT');
			} catch (Exception $e) {
				CMDBSource::Query('ROLLBACK');
				throw new Exception('|  Token migration aborted: unable to insert updated data into database column: '.$e->getMessage().'.');
			}

			SetupLog::Info("|  Token migration done: {$iMigrated} row(s) decrypted to clear string.");
		}
	}
}
