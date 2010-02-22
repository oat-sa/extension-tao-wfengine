<?php
/**
 * main configuration file
 *
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 *
 */

# plugins directory
define("DIR_PLUGIN"			, dirname(__FILE__). "/../plugins/");

# actions directory
define("DIR_ACTIONS"		, dirname(__FILE__). "/../actions/");

# models directory
define("DIR_MODELS"			, dirname(__FILE__). "/../models/");

# plugin directory
define('DIR_PLUGINS'		, dirname(__FILE__).'/../plugins/');

# views directory
define("DIR_VIEWS"			, dirname(__FILE__) . '/../views/');

# helpers directory
define("DIR_HELPERS"		, dirname(__FILE__) . "/../helpers/");



# session namespace
define('SESSION_NAMESPACE', 'ClearFw');

# default module name
define('DEFAULT_MODULE_NAME', 'Main');

#default action name
define('DEFAULT_ACTION_NAME', 'index');

# Process Browser page title.
define('PROCESS_BROWSER_TITLE', 'Process BrowserEngine');
										
# Next/Previous button usable or not.
define('USE_NEXT',			true);
define('USE_PREVIOUS',		true);
define('FORCE_NEXT',		true);

# Keyboard enabled or not.
define('USE_KEYBOARD',		true);


# Service mode
# If set to true, the process dashboard (main view) and the
# process creation feature are not available.
define('SERVICE_MODE', false);
										

# theme directory
$GLOBALS['dir_theme']		= "templates/";

# language
$GLOBALS['lang']			= 'EN';

#BASE PATH: the root path in the file system (usually the document root)
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
define('BASE_PATH', ROOT_PATH . '/wfEngine');

#BASE URL (usually the domain root)
define('ROOT_URL', 'http://'.$_SERVER['HTTP_HOST']);
define('BASE_URL', ROOT_URL.'/wfEngine');

#BASE WWW the web resources path
define('BASE_WWW', BASE_URL . '/views/' );


?>