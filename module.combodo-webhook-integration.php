<?php
//
// iTop module definition file
//
//
//
SetupWebPage::AddModule(__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'combodo-webhook-integration/1.2.0', array(
		// Identification
		//
		'label' => 'Webhook integrations',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			// Dependency to itop-config-mgmt should be v2.7.0, but we set it to v2.4.0 to enbale compatibility with some legacy iTop 2.7 customer packages
			'itop-config-mgmt/2.4.0 || itop-structure/3.0.0',
			// Dependency to this module is only here to force compatibility with iTop 2.7+, there is no functional dependency
			'itop-config/2.7.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			// Extension autoloader
			'vendor/autoload.php',
			// Explicitly load DM classes
			'SendWebRequest.php',
			'model.combodo-webhook-integration.php',
		),
		'webservice' => array(),
		'data.struct' => array(
			'data.struct.remote_application_type.xml',
		),
		'data.sample' => array(),

		// Documentation
		//
		'doc.manual_setup' => '', // hyperlink to manual setup documentation, if any
		'doc.more_information' => '', // hyperlink to more information, if any

		// Default settings
		//
		'settings' => array(),
	)
);