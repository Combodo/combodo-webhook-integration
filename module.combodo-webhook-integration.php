<?php
//
// iTop module definition file
//
//
//
SetupWebPage::AddModule(__FILE__, // Path to the current file, all other file names are relative to the directory containing this file
	'combodo-webhook-integration/0.3.0', array(
		// Identification
		//
		'label' => 'Webhook integration',
		'category' => 'business',

		// Setup
		//
		'dependencies' => array(
			'itop-config-mgmt/2.7.0',
		),
		'mandatory' => false,
		'visible' => true,

		// Components
		//
		'datamodel' => array(
			// Extension autoloader
			'vendor/autoload.php',
			// Explicitly load DM classes
			'src/Core/AsyncTask/SendWebRequest.php',
			'model.combodo-webhook-integration.php',
		),
		'webservice' => array(),
		'data.struct' => array(),
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
