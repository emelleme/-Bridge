<?php

/************************************************************************************
 ************************************************************************************
 **                                                                                **
 **  If you can read this text in your browser then you don't have PHP installed.  **
 **  Please install PHP 5.3.2 or higher, preferably PHP 5.3.4+.                    **
 **                                                                                **
 ************************************************************************************
 ************************************************************************************/

/**
 * SilverStripe CMS Installer
 * This installer doesn't use any of the fancy SilverStripe stuff in case it's unsupported.
 */

// speed up mysql_connect timeout if the server can't be found
ini_set('mysql.connect_timeout', 5);
ini_set('date.timezone', 'America/New_York');

ini_set('max_execution_time', 0);

error_reporting(E_ALL | E_STRICT);

// Include environment files
$usingEnv = false;
$envFileExists = false;
$envFiles = array('_ss_environment.php', '../_ss_environment.php', '../../_ss_environment.php');
foreach($envFiles as $envFile) {
	if(@file_exists($envFile)) {
		include_once($envFile);
		$envFileExists = true;
		$usingEnv = true;
		break;
	}
}

if($envFileExists) {
	if(!empty($_REQUEST['useEnv'])) {
		$usingEnv = true;
	} else {
		$usingEnv = false;
	}
}

require_once FRAMEWORK_NAME . '/dev/install/DatabaseConfigurationHelper.php';
require_once FRAMEWORK_NAME . '/dev/install/DatabaseAdapterRegistry.php';

// Set default locale, but try and sniff from the user agent
$defaultLocale = 'en_US';
$locales = array(
	'af_ZA' => array('Afrikaans', 'Afrikaans'),
	'sq_AL' => array('Albanian', 'shqip'),
	'ar_EG' => array('Arabic', '&#1575;&#1604;&#1593;&#1585;&#1576;&#1610;&#1577;'),
	'eu_ES' => array('Basque', 'euskera'),
	'be_BY' => array('Belarusian', '&#1041;&#1077;&#1083;&#1072;&#1088;&#1091;&#1089;&#1082;&#1072;&#1103; &#1084;&#1086;&#1074;&#1072;'),
	'bn_BD' => array('Bengali', '&#2476;&#2494;&#2434;&#2482;&#2494;'),
	'bg_BG' => array('Bulgarian', '&#1073;&#1098;&#1083;&#1075;&#1072;&#1088;&#1089;&#1082;&#1080;'),
	'ca_ES' => array('Catalan', 'catal&agrave;'),
	'zh_yue' => array('Chinese (Cantonese)', '&#24291;&#26481;&#35441; [&#24191;&#19996;&#35805;]'),
	'zh_cmn' => array('Chinese (Mandarin)', '&#26222;&#36890;&#35441; [&#26222;&#36890;&#35805;]'),
	'hr_HR' => array('Croatian', 'Hrvatski'),
	'cs_CZ' => array('Czech', '&#x010D;e&#353;tina'),
	'cy_GB' => array('Welsh', 'Welsh/Cymraeg'),
	'da_DK' => array('Danish', 'dansk'),
	'nl_NL' => array('Dutch', 'Nederlands'),
	'en_NZ' => array('English (NZ)', 'English (NZ)'),
	'en_US' => array('English (US)', 'English (US)'),
	'en_GB' => array('English (UK)', 'English (UK)'),
	'eo_XX' => array('Esperanto', 'Esperanto'),
	'et_EE' => array('Estonian', 'eesti keel'),
	'fo_FO' => array('Faroese', 'F&oslash;royska'),
	'fi_FI' => array('Finnish', 'suomi'),
	'fr_FR' => array('French', 'fran&ccedil;ais'),
	'gd_GB' => array('Gaelic', 'Gaeilge'),
	'gl_ES' => array('Galician', 'Galego'),
	'de_DE' => array('German', 'Deutsch'),
	'el_GR' => array('Greek', '&#949;&#955;&#955;&#951;&#957;&#953;&#954;&#940;'),
	'gu_IN' => array('Gujarati', '&#2711;&#2753;&#2716;&#2736;&#2750;&#2724;&#2752;'),
	'ha_NG' => array('Hausa', '&#1581;&#1614;&#1608;&#1618;&#1587;&#1614;'),
	'he_IL' => array('Hebrew', '&#1506;&#1489;&#1512;&#1497;&#1514;'),
	'hi_IN' => array('Hindi', '&#2361;&#2367;&#2344;&#2381;&#2342;&#2368;'),
	'hu_HU' => array('Hungarian', 'magyar'),
	'is_IS' => array('Icelandic', '&Iacute;slenska'),
	'id_ID' => array('Indonesian', 'Bahasa Indonesia'),
	'ga_IE' => array('Irish', 'Irish'),
	'it_IT' => array('Italian', 'italiano'),
	'ja_JP' => array('Japanese', '&#26085;&#26412;&#35486;'),
	'jv_ID' => array('Javanese', 'basa Jawa'),
	'ko_KR' => array('Korean', '&#54620;&#44397;&#50612; [&#38867;&#22283;&#35486;]'),
	'ku_IQ' => array('Kurdish', 'Kurd&iacute;'),
	'lv_LV' => array('Latvian', 'latvie&#353;u'),
	'lt_LT' => array('Lithuanian', 'lietuvi&#353;kai'),
	'mk_MK' => array('Macedonian', '&#1084;&#1072;&#1082;&#1077;&#1076;&#1086;&#1085;&#1089;&#1082;&#1080;'),
	'mi_NZ' => array('Maori', 'Maori'),
	'ms_MY' => array('Malay', 'Bahasa melayu'),
	'mt_MT' => array('Maltese', 'Malti'),
	'mr_IN' => array('Marathi', '&#2350;&#2352;&#2366;&#2336;&#2368;'),
	'ne_NP' => array('Nepali', '&#2344;&#2375;&#2346;&#2366;&#2354;&#2368;'),
	'nb_NO' => array('Norwegian', 'Norsk'),
	'om_ET' => array('Oromo', 'Afaan Oromo'),
	'fa_IR' => array('Persian', '&#1601;&#1575;&#1585;&#1587;&#1609;'),
	'pl_PL' => array('Polish', 'polski'),
	'pt_PT' => array('Portuguese (Portugal)', 'portugu&ecirc;s (Portugal)'),
	'pt_BR' => array('Portuguese (Brazil)', 'portugu&ecirc;s (Brazil)'),
	'pa_IN' => array('Punjabi', '&#2602;&#2672;&#2588;&#2622;&#2604;&#2624;'),
	'qu_PE' => array('Quechua', 'Quechua'),
	'rm_CH' => array('Romansh', 'rumantsch'),
	'ro_RO' => array('Romanian', 'rom&acirc;n'),
	'ru_RU' => array('Russian', '&#1056;&#1091;&#1089;&#1089;&#1082;&#1080;&#1081;'),
	'sco_SCO' => array('Scots', 'Scoats leid, Lallans'),
	'sr_RS' => array('Serbian', '&#1089;&#1088;&#1087;&#1089;&#1082;&#1080;'),
	'sk_SK' => array('Slovak', 'sloven&#269;ina'),
	'sl_SI' => array('Slovenian', 'sloven&#353;&#269;ina'),
	'es_ES' => array('Spanish', 'espa&ntilde;ol'),
	'sv_SE' => array('Swedish', 'Svenska'),
	'tl_PH' => array('Tagalog', 'Tagalog'),
	'ta_IN' => array('Tamil', '&#2980;&#2990;&#3007;&#2996;&#3021;'),
	'te_IN' => array('Telugu', '&#3108;&#3142;&#3122;&#3137;&#3095;&#3137;'),
	'to_TO' => array('Tonga', 'chiTonga'),
	'ts_ZA' => array('Tsonga', 'xiTshonga'),
	'tn_ZA' => array('Tswana', 'seTswana'),
	'tr_TR' => array('Turkish', 'T&uuml;rk&ccedil;e'),
	'tk_TM' => array('Turkmen', '&#1090;&#1199;&#1088;&#1082;m&#1077;&#1085;&#1095;&#1077;'),
	'tw_GH' => array('Twi', 'twi'),
	'uk_UA' => array('Ukrainian', '&#1059;&#1082;&#1088;&#1072;&#1111;&#1085;&#1089;&#1100;&#1082;&#1072;'),
	'ur_PK' => array('Urdu', '&#1575;&#1585;&#1583;&#1608;'),
	'uz_UZ' => array('Uzbek', '&#1118;&#1079;&#1073;&#1077;&#1082;'),
	've_ZA' => array('Venda', 'tshiVen&#x1E13;a'),
	'vi_VN' => array('Vietnamese', 'ti&#7871;ng vi&#7879;t'),
	'wo_SN' => array('Wolof', 'Wollof'),
	'xh_ZA' => array('Xhosa', 'isiXhosa'),
	'zu_ZA' => array('Zulu', 'isiZulu'),
);

// Discover which databases are available
DatabaseAdapterRegistry::autodiscover();

// Determine which external database modules are USABLE
foreach(DatabaseAdapterRegistry::get_adapters() as $class => $details) {
	$databaseClasses[$class] = $details;
	if(file_exists($details['helperPath'])) {
		$databaseClasses[$class]['hasModule'] = true;
		include_once($details['helperPath']);
	} else {
		$databaseClasses[$class]['hasModule'] = false;
	}
}

// Load database config
if(isset($_REQUEST['db'])) {
	if(isset($_REQUEST['db']['type'])) $type = $_REQUEST['db']['type'];
	else $type = $_REQUEST['db']['type'] = defined('SS_DATABASE_CLASS') ? SS_DATABASE_CLASS : 'MySQLDatabase';

	// Disabled inputs don't submit anything - we need to use the environment (except the database name)
	if($usingEnv) {
		$_REQUEST['db'][$type] = $databaseConfig = array(
			"type" => defined('SS_DATABASE_CLASS') ? SS_DATABASE_CLASS : $type,
			"server" => defined('SS_DATABASE_SERVER') ? SS_DATABASE_SERVER : "localhost",
			"username" => defined('SS_DATABASE_USERNAME') ? SS_DATABASE_USERNAME : "root",
			"password" => defined('SS_DATABASE_PASSWORD') ? SS_DATABASE_PASSWORD : "",
			"database" => $_REQUEST['db'][$type]['database'],
		);

	} else {
		// Normal behaviour without the environment
		$databaseConfig = $_REQUEST['db'][$type];
		$databaseConfig['type'] = $type;
	}
} else {
	$type = $_REQUEST['db']['type'] = defined('SS_DATABASE_CLASS') ? SS_DATABASE_CLASS : 'MySQLDatabase';
	$_REQUEST['db'][$type] = $databaseConfig = array(
		"type" => $type,
		"server" => defined('SS_DATABASE_SERVER') ? SS_DATABASE_SERVER : "localhost",
		"username" => defined('SS_DATABASE_USERNAME') ? SS_DATABASE_USERNAME : "root",
		"password" => defined('SS_DATABASE_PASSWORD') ? SS_DATABASE_PASSWORD : "",
		"database" => isset($_SERVER['argv'][2]) ? $_SERVER['argv'][2] : "SS_mysite",
	);
}

if(isset($_REQUEST['admin'])) {
	// Disabled inputs don't submit anything - we need to use the environment (except the database name)
	if($usingEnv) {
		$_REQUEST['admin'] = $adminConfig = array(
			'username' => defined('SS_DEFAULT_ADMIN_USERNAME') ? SS_DEFAULT_ADMIN_USERNAME : 'admin',
			'password' => defined('SS_DEFAULT_ADMIN_PASSWORD') ? SS_DEFAULT_ADMIN_PASSWORD : '',
		);
	} else {
		$adminConfig = $_REQUEST['admin'];
	}
} else {
	$_REQUEST['admin'] = $adminConfig = array(
		'username' => defined('SS_DEFAULT_ADMIN_USERNAME') ? SS_DEFAULT_ADMIN_USERNAME : 'admin',
		'password' => defined('SS_DEFAULT_ADMIN_PASSWORD') ? SS_DEFAULT_ADMIN_PASSWORD : '',
	);
}

$alreadyInstalled = false;
if(file_exists('mysite/_config.php')) {
	// Find the $database variable in the relevant config file without having to execute the config file
	if(preg_match("/\\\$database\s*=\s*[^\n\r]+[\n\r]/", file_get_contents("mysite/_config.php"), $parts)) {
		eval($parts[0]);
		if($database) $alreadyInstalled = true;
	// Assume that if $databaseConfig is defined in mysite/_config.php, then a non-environment-based installation has
	// already gone ahead
	} else if(preg_match("/\\\$databaseConfig\s*=\s*[^\n\r]+[\n\r]/", file_get_contents("mysite/_config.php"), $parts)) {
		$alreadyInstalled = true;
	}
}

if(file_exists(FRAMEWORK_NAME . '/silverstripe_version')) {
	$silverstripe_version = file_get_contents(FRAMEWORK_NAME . '/silverstripe_version');
} else {
	$silverstripe_version = "unknown";
}

// Check requirements
$req = new InstallRequirements();
$req->check();

$webserverConfigFile = '';
if($req->isIIS()) {
	$webserverConfigFile = 'web.config';
} else {
	$webserverConfigFile = '.htaccess';
}

if($req->hasErrors()) {
	$hasErrorOtherThanDatabase = true;
	$phpIniLocation = php_ini_loaded_file();
}

if($databaseConfig) {
	$dbReq = new InstallRequirements();
	$dbReq->checkDatabase($databaseConfig);
}

if($adminConfig) {
	$adminReq = new InstallRequirements();
	$adminReq->checkAdminConfig($adminConfig);
}

// Actual processor
$installFromCli = (isset($_SERVER['argv'][1]) && $_SERVER['argv'][1] == 'install');

// CLI-install error message.  exit(1) will halt any makefile.
if($installFromCli && ($req->hasErrors() || $dbReq->hasErrors())) {
	echo "Cannot install due to errors:\n";
	$req->listErrors();
	$dbReq->listErrors();
	exit(1);
}

if((isset($_REQUEST['go']) || $installFromCli) && !$req->hasErrors() && !$dbReq->hasErrors() && $adminConfig['username'] && $adminConfig['password']) {
	// Confirm before reinstalling
	if(!$installFromCli && $alreadyInstalled) {
		include(FRAMEWORK_NAME . '/dev/install/config-form.html');

	} else {
		$inst = new Installer();
		if($_REQUEST) $inst->install($_REQUEST);
		else $inst->install(array(
			'db' => $databaseConfig,
			'admin' => $adminConfig,
		));
	}

// Show the config form
} else {
	include(FRAMEWORK_NAME . '/dev/install/config-form.html');
}

/**
 * This class checks requirements
 * Each of the requireXXX functions takes an argument which gives a user description of the test.
 * It's an array of 3 parts:
 *  $description[0] - The test catetgory
 *  $description[1] - The test title
 *  $description[2] - The test error to show, if it goes wrong
 */
class InstallRequirements {
	var $errors, $warnings, $tests;

	/**
	 * Check the database configuration. These are done one after another
	 * starting with checking the database function exists in PHP, and
	 * continuing onto more difficult checks like database permissions.
	 */
	function checkDatabase($databaseConfig) {
		if($this->requireDatabaseFunctions(
			$databaseConfig,
			array(
				"Database Configuration",
				"Database support",
				"Database support in PHP",
				$this->getDatabaseTypeNice($databaseConfig['type'])
			)
		)) {
			if($this->requireDatabaseServer(
				$databaseConfig,
				array(
					"Database Configuration",
					"Database server",
					$databaseConfig['type'] == 'SQLiteDatabase' ? "I couldn't write to path '$databaseConfig[path]'" : "I couldn't find a database server on '$databaseConfig[server]'",
					$databaseConfig['type'] == 'SQLiteDatabase' ? $databaseConfig['path'] : $databaseConfig['server']
				)
			)) {
				if($this->requireDatabaseConnection(
					$databaseConfig,
					array(
						"Database Configuration",
						"Database access credentials correct",
						"That username/password doesn't work"
					)
				)) {
					if($this->requireDatabaseVersion(
						$databaseConfig,
						array(
							"Database Configuration",
							"Database server meets required version",
							'',
							'Version ' . $this->getDatabaseConfigurationHelper($databaseConfig['type'])->getDatabaseVersion($databaseConfig)
						)
					)) {
						$this->requireDatabaseOrCreatePermissions(
							$databaseConfig,
							array(
								"Database Configuration",
								"Can I access/create the database",
								"I can't create new databases and the database '$databaseConfig[database]' doesn't exist"
							)
						);
					}
				}
			}
		}
	}

	function checkAdminConfig($adminConfig) {
		if(!$adminConfig['username']) {
			$this->error(array('', 'Please enter a username!'));
		}
		if(!$adminConfig['password']) {
			$this->error(array('', 'Please enter a password!'));
		}
	}

	/**
	 * Check if the web server is IIS.
	 * @return boolean
	 */
	function isIIS($version = 7) {
		if(strpos($this->findWebserver(), 'IIS/' . $version) !== false) {
			return true;
		} else {
			return false;
		}
	}

	function isApache() {
		if(strpos($this->findWebserver(), 'Apache') !== false) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Find the webserver software running on the PHP host.
	 * @return string|boolean Server software or boolean FALSE
	 */
	function findWebserver() {
		// Try finding from SERVER_SIGNATURE or SERVER_SOFTWARE
		$webserver = @$_SERVER['SERVER_SIGNATURE'];
		if(!$webserver) $webserver = @$_SERVER['SERVER_SOFTWARE'];

		if($webserver) return strip_tags(trim($webserver));
		else return false;
	}

	/**
	 * Check everything except the database
	 */
	function check() {
		$this->errors = null;
		$isApache = $this->isApache();
		$isIIS = $this->isIIS(7);
		$webserver = $this->findWebserver();

		$this->requirePHPVersion('5.3.4', '5.3.2', array("PHP Configuration", "PHP5 installed", null, "PHP version " . phpversion()));

		// Check that we can identify the root folder successfully
		$this->requireFile(FRAMEWORK_NAME . '/dev/install/config-form.html', array("File permissions",
			"Does the webserver know where files are stored?",
			"The webserver isn't letting me identify where files are stored.",
			$this->getBaseDir()
		));

		$this->requireModule('mysite', array("File permissions", "mysite/ directory exists?"));
		$this->requireModule(FRAMEWORK_NAME, array("File permissions", FRAMEWORK_NAME . "/ directory exists?"));

		if($isApache) {
			$this->requireWriteable('.htaccess', array("File permissions", "Is the .htaccess file writeable?", null));
		} elseif($isIIS) {
			$this->requireWriteable('web.config', array("File permissions", "Is the web.config file writeable?", null));
		}

		$this->requireWriteable('mysite/_config.php', array("File permissions", "Is the mysite/_config.php file writeable?", null));
		if (!$this->checkModuleExists('cms')) {
			$this->requireWriteable('mysite/code/RootURLController.php', array("File permissions", "Is the mysite/code/RootURLController.php file writeable?", null));
		}
		$this->requireWriteable('assets', array("File permissions", "Is the assets/ directory writeable?", null));

		$tempFolder = $this->getTempFolder();
		$this->requireTempFolder(array('File permissions', 'Is a temporary directory available?', null, $tempFolder));
		if($tempFolder) {
			// in addition to the temp folder being available, check it is writable
			$this->requireWriteable($tempFolder, array("File permissions", sprintf("Is the temporary directory writeable?", $tempFolder), null), true);
		}

		// Check for web server, unless we're calling the installer from the command-line
		$this->isRunningWebServer(array("Webserver Configuration", "Server software", "Unknown", $webserver));

		if($isApache) {
			$this->requireApacheRewriteModule('mod_rewrite', array("Webserver Configuration", "URL rewriting support", "You need mod_rewrite to use friendly URLs with SilverStripe, but it is not enabled."));
		} elseif($isIIS) {
			$this->requireIISRewriteModule('IIS_UrlRewriteModule', array("Webserver Configuration", "URL rewriting support", "You need to enable the IIS URL Rewrite Module to use friendly URLs with SilverStripe, but it is not installed or enabled. Download it for IIS 7 from http://www.iis.net/expand/URLRewrite"));
		} else {
			$this->warning(array("Webserver Configuration", "URL rewriting support", "I can't tell whether any rewriting module is running.  You may need to configure a rewriting rule yourself."));
		}

		$this->requireServerVariables(array('SCRIPT_NAME','HTTP_HOST','SCRIPT_FILENAME'), array("Webserver config", "Recognised webserver", "You seem to be using an unsupported webserver.  The server variables SCRIPT_NAME, HTTP_HOST, SCRIPT_FILENAME need to be set."));

		// Check for GD support
		if(!$this->requireFunction("imagecreatetruecolor", array("PHP Configuration", "GD2 support", "PHP must have GD version 2."))) {
			$this->requireFunction("imagecreate", array("PHP Configuration", "GD2 support", "GD support for PHP not included."));
		}

		// Check for XML support
		$this->requireFunction('xml_set_object', array("PHP Configuration", "XML support", "XML support not included in PHP."));
		$this->requireClass('DOMDocument', array("PHP Configuration", "DOM/XML support", "DOM/XML support not included in PHP."));

		// Check for token_get_all
		$this->requireFunction('token_get_all', array("PHP Configuration", "PHP Tokenizer", "PHP tokenizer support not included in PHP."));

		// Check for session support
		$this->requireFunction('session_start', array('PHP Configuration', 'Session support', 'Session support not included in PHP.'));

		// Check for iconv support
		$this->requireFunction('iconv', array('PHP Configuration', 'iconv support', 'iconv support not included in PHP.'));

		// Check for hash support
		$this->requireFunction('hash', array('PHP Configuration', 'hash support', 'hash support not included in PHP.'));

		// Check for Reflection support
		$this->requireClass('ReflectionClass', array('PHP Configuration', 'Reflection support', 'Reflection support not included in PHP.'));

		// Check for Standard PHP Library (SPL) support
		$this->requireFunction('spl_classes', array('PHP Configuration', 'SPL support', 'Standard PHP Library (SPL) not included in PHP.'));

		$this->requireDateTimezone(array('PHP Configuration', 'date.timezone set and valid', 'date.timezone option in php.ini must be set correctly.', ini_get('date.timezone')));

		$this->suggestPHPSetting('asp_tags', array(false,0,''), array('PHP Configuration', 'asp_tags option turned off', 'This should be turned off as it can cause issues with SilverStripe'));
		$this->suggestPHPSetting('magic_quotes_gpc', array(false,0,''), array('PHP Configuration', 'magic_quotes_gpc option turned off', 'This should be turned off, as it can cause issues with cookies. More specifically, unserializing data stored in cookies.'));
		$this->suggestPHPSetting('display_errors', array(false,0,''), array('PHP Configuration', 'display_errors option turned off', 'Unless you\'re in a development environment, this should be turned off, as it can expose sensitive data to website users.'));

		// Check memory allocation
		$this->requireMemory(32*1024*1024, 64*1024*1024, array("PHP Configuration", "Memory allocated (PHP config option 'memory_limit')", "SilverStripe needs a minimum of 32M allocated to PHP, but recommends 64M.", ini_get("memory_limit")));

		return $this->errors;
	}

	function suggestPHPSetting($settingName, $settingValues, $testDetails) {
		$this->testing($testDetails);
		$val = ini_get($settingName);
		if(!in_array($val, $settingValues) && $val != $settingValues) {
			$testDetails[2] = "$settingName is set to '$val' in php.ini.  $testDetails[2]";
			$this->warning($testDetails);
		}
	}

	function requireDateTimezone($testDetails) {
		$this->testing($testDetails);
		$result = ini_get('date.timezone') && in_array(ini_get('date.timezone'), timezone_identifiers_list());
		if(!$result) {
			$this->error($testDetails);
		}
	}

	function requireMemory($min, $recommended, $testDetails) {
		$_SESSION['forcemem'] = false;

		$mem = $this->getPHPMemory();
		if($mem < (64 * 1024 * 1024)) {
			ini_set('memory_limit', '64M');
			$mem = $this->getPHPMemory();
			$testDetails[3] = ini_get("memory_limit");
		}

		$this->testing($testDetails);

		if($mem < $min && $mem > 0) {
			$testDetails[2] .= " You only have " . ini_get("memory_limit") . " allocated";
			$this->error($testDetails);
		} else if($mem < $recommended && $mem > 0) {
			$testDetails[2] .= " You only have " . ini_get("memory_limit") . " allocated";
			$this->warning($testDetails);
		} elseif($mem == 0) {
			$testDetails[2] .= " We can't determine how much memory you have allocated. Install only if you're sure you've allocated at least 20 MB.";
			$this->warning($testDetails);
		}
	}

	function getPHPMemory() {
		$memString = ini_get("memory_limit");

		switch(strtolower(substr($memString,-1))) {
			case "k":
				return round(substr($memString,0,-1)*1024);

			case "m":
				return round(substr($memString,0,-1)*1024*1024);

			case "g":
				return round(substr($memString,0,-1)*1024*1024*1024);

			default:
				return round($memString);
		}
	}

	function listErrors() {
		if($this->errors) {
			echo "<p>The following problems are preventing me from installing SilverStripe CMS:</p>\n\n";
			foreach($this->errors as $error) {
				echo "<li>" . htmlentities(implode(", ", $error), ENT_COMPAT, 'UTF-8') . "</li>\n";
			}
		}
	}

	function showTable($section = null) {
		if($section) {
			$tests = $this->tests[$section];
			$id = strtolower(str_replace(' ', '_', $section));
			echo "<table id=\"{$id}_results\" class=\"testResults\" width=\"100%\">";
			foreach($tests as $test => $result) {
				echo "<tr class=\"$result[0]\"><td>$test</td><td>" . nl2br(htmlentities($result[1], ENT_COMPAT, 'UTF-8')) . "</td></tr>";
			}
			echo "</table>";

		} else {
			foreach($this->tests as $section => $tests) {
				$failedRequirements = 0;
				$warningRequirements = 0;

				$output = "";

				foreach($tests as $test => $result) {
					if(isset($result['0'])) {
						switch($result['0']) {
							case 'error':
								$failedRequirements++;
								break;
							case 'warning':
								$warningRequirements++;
								break;
						}
					}
					$output .= "<tr class=\"$result[0]\"><td>$test</td><td>" . nl2br(htmlentities($result[1], ENT_COMPAT, 'UTF-8')) . "</td></tr>";
				}
				$className = "good";
				$text = "All Requirements Pass";
				$pluralWarnings = ($warningRequirements == 1) ? 'Warning' : 'Warnings';

				if($failedRequirements > 0) {
					$className = "error";
					$pluralWarnings = ($warningRequirements == 1) ? 'Warning' : 'Warnings';

					$text = $failedRequirements . ' Failed and '. $warningRequirements . ' '. $pluralWarnings;
				}
				else if($warningRequirements > 0) {
					$className = "warning";
					$text = "All Requirements Pass but ". $warningRequirements . ' '. $pluralWarnings;
				}

				echo "<h5 class='requirement $className'>$section <a href='#'>Show All Requirements</a> <span>$text</span></h5>";
				echo "<table class=\"testResults\">";
				echo $output;
				echo "</table>";
			}
		}
	}

	function requireFunction($funcName, $testDetails) {
		$this->testing($testDetails);
		if(!function_exists($funcName)) $this->error($testDetails);
		else return true;
	}

	function requireClass($className, $testDetails) {
		$this->testing($testDetails);
		if(!class_exists($className)) $this->error($testDetails);
		else return false;
	}

	/**
	 * Require that the given class doesn't exist
	 */
	function requireNoClasses($classNames, $testDetails) {
		$this->testing($testDetails);
		$badClasses = array();
		foreach($classNames as $className) {
			if(class_exists($className)) $badClasses[] = $className;
		}
		if($badClasses) {
			$testDetails[2] .= ".  The following classes are at fault: " . implode(', ', $badClasses);
			$this->error($testDetails);
		}
		else return true;
	}

	function requirePHPVersion($recommendedVersion, $requiredVersion, $testDetails) {
		$this->testing($testDetails);

		$installedVersion = phpversion();

		if(version_compare($installedVersion, $requiredVersion, '<')) {
			$testDetails[2] = "SilverStripe requires PHP version $requiredVersion or later.\n
				PHP version $installedVersion is currently installed.\n
				While SilverStripe requires at least PHP version $requiredVersion, upgrading to $recommendedVersion or later is recommended.\n
				If you are installing SilverStripe on a shared web server, please ask your web hosting provider to upgrade PHP for you.";
			$this->error($testDetails);
			return;
		}

		if(version_compare($installedVersion, $recommendedVersion, '<')) {
			$testDetails[2] = "PHP version $installedVersion is currently installed.\n
				Upgrading to at least PHP version $recommendedVersion is recommended.\n
				SilverStripe should run, but you may run into issues. Future releases may require a later version of PHP.\n";
			$this->warning($testDetails);
			return;
		}

		return true;
	}

	/**
	 * Check that a module exists
	 */
	function checkModuleExists($dirname) {
		$path = $this->getBaseDir() . $dirname;
		return file_exists($path) && ($dirname == 'mysite' || file_exists($path . '/_config.php'));
	}

	/**
	 * The same as {@link requireFile()} but does additional checks
	 * to ensure the module directory is intact.
	 */
	function requireModule($dirname, $testDetails) {
		$this->testing($testDetails);
		$path = $this->getBaseDir() . $dirname;
		if(!file_exists($path)) {
			$testDetails[2] .= " Directory '$path' not found. Please make sure you have uploaded the SilverStripe files to your webserver correctly.";
			$this->error($testDetails);
		} elseif(!file_exists($path . '/_config.php') && $dirname != 'mysite') {
			$testDetails[2] .= " Directory '$path' exists, but is missing files. Please make sure you have uploaded the SilverStripe files to your webserver correctly.";
			$this->error($testDetails);
		}
	}

	function requireFile($filename, $testDetails) {
		$this->testing($testDetails);
		$filename = $this->getBaseDir() . $filename;
		if(!file_exists($filename)) {
			$testDetails[2] .= " (file '$filename' not found)";
			$this->error($testDetails);
		}
	}

	function requireWriteable($filename, $testDetails, $absolute = false) {
		$this->testing($testDetails);

		if($absolute) {
			$filename = str_replace('/', DIRECTORY_SEPARATOR, $filename);
		} else {
			$filename = $this->getBaseDir() . str_replace('/', DIRECTORY_SEPARATOR, $filename);
		}

		if(file_exists($filename)) $isWriteable = is_writeable($filename);
		else $isWriteable = is_writeable(dirname($filename));

		if(!$isWriteable) {
			if(function_exists('posix_getgroups')) {
				$userID = posix_geteuid();
				$user = posix_getpwuid($userID);

				$currentOwnerID = fileowner(file_exists($filename) ? $filename : dirname($filename) );
				$currentOwner = posix_getpwuid($currentOwnerID);

				$testDetails[2] .= "User '$user[name]' needs to be able to write to this file:\n$filename\n\nThe file is currently owned by '$currentOwner[name]'.  ";

				if($user['name'] == $currentOwner['name']) {
					$testDetails[2] .= "We recommend that you make the file writeable.";
				} else {

					$groups = posix_getgroups();
					$groupList = array();
					foreach($groups as $group) {
						$groupInfo = posix_getgrgid($group);
						if(in_array($currentOwner['name'], $groupInfo['members'])) $groupList[] = $groupInfo['name'];
					}
					if($groupList) {
						$testDetails[2] .= "	We recommend that you make the file group-writeable and change the group to one of these groups:\n - ". implode("\n - ", $groupList)
							. "\n\nFor example:\nchmod g+w $filename\nchgrp " . $groupList[0] . " $filename";
					} else {
						$testDetails[2] .= "  There is no user-group that contains both the web-server user and the owner of this file.  Change the ownership of the file, create a new group, or temporarily make the file writeable by everyone during the install process.";
					}
				}

			} else {
				$testDetails[2] .= "The webserver user needs to be able to write to this file:\n$filename";
			}

			$this->error($testDetails);
		}
	}

	function getTempFolder() {
		$sysTmp = sys_get_temp_dir();
		$worked = true;
		$ssTmp = "$sysTmp/silverstripe-cache";

		if(!@file_exists($ssTmp)) {
			@$worked = mkdir($ssTmp);
		}

		if(!$worked) {
			$ssTmp = dirname($_SERVER['SCRIPT_FILENAME']) . '/silverstripe-cache';
			$worked = true;
			if(!@file_exists($ssTmp)) {
				@$worked = mkdir($ssTmp);
			}
		}

		if($worked) return $ssTmp;

		return false;
	}

	function requireTempFolder($testDetails) {
		$this->testing($testDetails);

		$tempFolder = $this->getTempFolder();
		if(!$tempFolder) {
			$testDetails[2] = "Permission problem gaining access to a temp directory. " .
				"Please create a folder named silverstripe-cache in the base directory " .
				"of the installation and ensure it has the adequate permissions.";
			$this->error($testDetails);
		}
	}

	function requireApacheModule($moduleName, $testDetails) {
		$this->testing($testDetails);
		if(!in_array($moduleName, apache_get_modules())) {
			$this->error($testDetails);
			return false;
		} else {
			return true;
		}
	}

	function testApacheRewriteExists($moduleName = 'mod_rewrite') {
		if(function_exists('apache_get_modules') && in_array($moduleName, apache_get_modules())) {
			return true;
		} elseif(isset($_SERVER['HTTP_MOD_REWRITE']) && $_SERVER['HTTP_MOD_REWRITE'] == 'On') {
			return true;
		} else {
			return false;
		}
	}

	function testIISRewriteModuleExists($moduleName = 'IIS_UrlRewriteModule') {
		if(isset($_SERVER[$moduleName]) && $_SERVER[$moduleName]) {
			return true;
		} else {
			return false;
		}
	}

	function requireApacheRewriteModule($moduleName, $testDetails) {
		$this->testing($testDetails);
		if($this->testApacheRewriteExists()) {
			return true;
		} else {
			$this->warning($testDetails);
			return false;
		}
	}

	/**
	 * Determines if the web server has any rewriting capability.
	 * @return boolean
	 */
	function hasRewritingCapability() {
		return ($this->testApacheRewriteExists() || $this->testIISRewriteModuleExists());
	}

	function requireIISRewriteModule($moduleName, $testDetails) {
		$this->testing($testDetails);
		if($this->testIISRewriteModuleExists()) {
			return true;
		} else {
			$this->warning($testDetails);
			return false;
		}
	}

	function getDatabaseTypeNice($databaseClass) {
		return substr($databaseClass, 0, -8);
	}

	/**
	 * Get an instance of a helper class for the specific database.
	 * @param string $databaseClass e.g. MySQLDatabase or MSSQLDatabase
	 */
	function getDatabaseConfigurationHelper($databaseClass) {
		$adapters = DatabaseAdapterRegistry::get_adapters();
		if(isset($adapters[$databaseClass])) {
			$helperPath = $adapters[$databaseClass]['helperPath'];
			$class = str_replace('.php', '', basename($helperPath));
		}
		return (class_exists($class)) ? new $class() : new MySQLDatabaseConfigurationHelper();
	}

	function requireDatabaseFunctions($databaseConfig, $testDetails) {
		$this->testing($testDetails);
		$helper = $this->getDatabaseConfigurationHelper($databaseConfig['type']);
		$result = $helper->requireDatabaseFunctions($databaseConfig);
		if($result) {
			return true;
		} else {
			$this->error($testDetails);
			return false;
		}
	}

	function requireDatabaseConnection($databaseConfig, $testDetails) {
		$this->testing($testDetails);
		$helper = $this->getDatabaseConfigurationHelper($databaseConfig['type']);
		$result = $helper->requireDatabaseConnection($databaseConfig);
		if($result['success']) {
			return true;
		} else {
			$testDetails[2] .= ": " . $result['error'];
			$this->error($testDetails);
			return false;
		}
	}

	function requireDatabaseVersion($databaseConfig, $testDetails) {
		$this->testing($testDetails);
		$helper = $this->getDatabaseConfigurationHelper($databaseConfig['type']);
		if(method_exists($helper, 'requireDatabaseVersion')) {
			$result = $helper->requireDatabaseVersion($databaseConfig);
			if($result['success']) {
				return true;
			} else {
				$testDetails[2] .= $result['error'];
				$this->warning($testDetails);
				return false;
			}
		}
		// Skipped test because this database has no required version
		return true;
	}

	function requireDatabaseServer($databaseConfig, $testDetails) {
		$this->testing($testDetails);
		$helper = $this->getDatabaseConfigurationHelper($databaseConfig['type']);
		$result = $helper->requireDatabaseServer($databaseConfig);
		if($result['success']) {
			return true;
		} else {
			$testDetails[2] .= ": " . $result['error'];
			$this->error($testDetails);
			return false;
		}
	}

	function requireDatabaseOrCreatePermissions($databaseConfig, $testDetails) {
		$this->testing($testDetails);
		$helper = $this->getDatabaseConfigurationHelper($databaseConfig['type']);
		$result = $helper->requireDatabaseOrCreatePermissions($databaseConfig);
		if($result['success']) {
			if($result['alreadyExists']) $testDetails[3] = "Database $databaseConfig[database]";
			else $testDetails[3] = "Able to create a new database";
			$this->testing($testDetails);
			return true;
		} else {
			if(!@$result['cannotCreate']) {
				$testDetails[2] .= ". Please create the database manually.";
			} else {
				$testDetails[2] .= " (user '$databaseConfig[username]' doesn't have CREATE DATABASE permissions.)";
			}

			$this->error($testDetails);
			return false;
		}
	}

	function requireServerVariables($varNames, $errorMessage) {
		//$this->testing($testDetails);
		foreach($varNames as $varName) {
			if(!$_SERVER[$varName]) $missing[] = '$_SERVER[' . $varName . ']';
		}
		if(!isset($missing)) {
			return true;
		} else {
			$testDetails[2] .= " (the following PHP variables are missing: " . implode(", ", $missing) . ")";
			$this->error($testDetails);
		}
	}

	function isRunningWebServer($testDetails) {
		$this->testing($testDetails);
		if($testDetails[3]) {
			return true;
		} else {
			$this->warning($testDetails);
			return false;
		}
	}

	// Must be PHP4 compatible
	var $baseDir;
	function getBaseDir() {
		// Cache the value so that when the installer mucks with SCRIPT_FILENAME half way through, this method
		// still returns the correct value.
		if(!$this->baseDir) $this->baseDir = realpath(dirname($_SERVER['SCRIPT_FILENAME'])) . DIRECTORY_SEPARATOR;
		return $this->baseDir;
	}

	function testing($testDetails) {
		if(!$testDetails) return;

		$section = $testDetails[0];
		$test = $testDetails[1];

		$message = "OK";
		if(isset($testDetails[3])) $message .= " ($testDetails[3])";

		$this->tests[$section][$test] = array("good", $message);
	}

	function error($testDetails) {
		$section = $testDetails[0];
		$test = $testDetails[1];
var_dump($testDetails);
		$this->tests[$section][$test] = array("error", $testDetails[2]);
		$this->errors[] = $testDetails;
	}

	function warning($testDetails) {
		$section = $testDetails[0];
		$test = $testDetails[1];

		$this->tests[$section][$test] = array("warning", $testDetails[2]);
		$this->warnings[] = $testDetails;
	}

	function hasErrors() {
		return sizeof($this->errors);
	}

	function hasWarnings() {
		return sizeof($this->warnings);
	}

}

class Installer extends InstallRequirements {
	function __construct() {
		// Cache the baseDir value
		$this->getBaseDir();
	}

	function install($config) {
?>
<html>
	<head>
		<title>Installing SilverStripe...</title>
		<link rel="stylesheet" type="text/css" href="<?php echo FRAMEWORK_NAME; ?>/dev/install/css/install.css" />
		<script src="<?php echo FRAMEWORK_NAME; ?>/thirdparty/jquery/jquery.js"></script>
	</head>
	<body>
		<div class="install-header">
			<div class="inner">
				<div class="brand">
					<span class="logo"></span>
					<h1>SilverStripe</h1>
				</div>
			</div>	
		</div>

		<div id="Navigation">&nbsp;</div>
		<div class="clear"><!-- --></div>

		<div class="main">
			<div class="inner">
				<h2>Installing SilverStripe...</h2>
				<p>I am now running through the installation steps (this should take about 30 seconds)</p>
				<p>If you receive a fatal error, refresh this page to continue the installation</p>
				<ul>
<?php

		$webserver = $this->findWebserver();
		$isIIS = $this->isIIS();
		$isApache = $this->isApache();

		flush();

		if(isset($config['stats'])) {
			if(file_exists(FRAMEWORK_NAME . '/silverstripe_version')) {
				$silverstripe_version = file_get_contents(FRAMEWORK_NAME . '/silverstripe_version');
			} else {
				$silverstripe_version = "unknown";
			}

			$phpVersion = urlencode(phpversion());
			$encWebserver = urlencode($webserver);
			$dbType = $config['db']['type'];

			// Try to determine the database version from the helper
			$databaseVersion = $config['db']['type'];
			$helper = $this->getDatabaseConfigurationHelper($dbType);
			if($helper && method_exists($helper, 'getDatabaseVersion')) {
				$databaseVersion = urlencode($dbType . ': ' . $helper->getDatabaseVersion($config['db'][$dbType]));
			}

			$url = "http://ss2stat.silverstripe.com/Installation/add?SilverStripe=$silverstripe_version&PHP=$phpVersion&Database=$databaseVersion&WebServer=$encWebserver";

			if(isset($_SESSION['StatsID']) && $_SESSION['StatsID']) {
				$url .= '&ID=' . $_SESSION['StatsID'];
			}

			@$_SESSION['StatsID'] = file_get_contents($url);
		}

		if(file_exists('mysite/_config.php')) {
			// Truncate the contents of _config instead of deleting it - we can't re-create it because Windows handles permissions slightly
			// differently to UNIX based filesystems - it takes the permissions from the parent directory instead of retaining them
			$fh = fopen('mysite/_config.php', 'wb');
			fclose($fh);
		}
		$theme = isset($_POST['template']) ? $_POST['template'] : 'simple';
		$locale = isset($_POST['locale']) ? $_POST['locale'] : 'en_US';
		$type = $config['db']['type'];
		$dbConfig = $config['db'][$type];
		if(!isset($dbConfig['path'])) $dbConfig['path'] = '';
		if(!$dbConfig) {
			echo "<p style=\"color: red\">Bad config submitted</p><pre>";
			print_r($config);
			echo "</pre>";
			die();
		}

		// Write the config file
		global $usingEnv;
		if($usingEnv) {

			$this->statusMessage("Setting up 'mysite/_config.php' for use with _ss_environment.php...");
			$this->writeToFile("mysite/_config.php", <<<PHP
<?php

global \$project;
\$project = 'mysite';

global \$database;
\$database = '{$dbConfig['database']}';

require_once('conf/ConfigureFromEnv.php');

MySQLDatabase::set_connection_charset('utf8');

// Set the current theme. More themes can be downloaded from
// http://www.silverstripe.org/themes/
SSViewer::set_theme('$theme');

// Set the site locale
i18n::set_locale('$locale');

// Enable nested URLs for this site (e.g. page/sub-page/)
if (class_exists('SiteTree')) SiteTree::enable_nested_urls();
PHP
			);

		} else {
			$this->statusMessage("Setting up 'mysite/_config.php'...");
			$escapedPassword = addslashes($dbConfig['password']);
			$this->writeToFile("mysite/_config.php", <<<PHP
<?php

global \$project;
\$project = 'mysite';

global \$databaseConfig;
\$databaseConfig = array(
	"type" => '{$type}',
	"server" => '{$dbConfig['server']}',
	"username" => '{$dbConfig['username']}',
	"password" => '{$escapedPassword}',
	"database" => '{$dbConfig['database']}',
	"path" => '{$dbConfig['path']}',
);

MySQLDatabase::set_connection_charset('utf8');

// Set the current theme. More themes can be downloaded from
// http://www.silverstripe.org/themes/
SSViewer::set_theme('$theme');

// Set the site locale
i18n::set_locale('$locale');

// Enable nested URLs for this site (e.g. page/sub-page/)
if (class_exists('SiteTree')) SiteTree::enable_nested_urls();
PHP
			);
		}

		if (!$this->checkModuleExists('cms')) {
			$this->writeToFile("mysite/code/RootURLController.php", <<<PHP
<?php

class RootURLController extends Controller {

	function index() {
		echo "<html>Your site is now set up. Start adding controllers to mysite to get started.</html>";
	}

}
PHP
			);
		}

		// Write the appropriate web server configuration file for rewriting support
		if($this->hasRewritingCapability()) {
			if($isApache) {
				$this->statusMessage("Setting up '.htaccess' file...");
				$this->createHtaccess();
			} elseif($isIIS) {
				$this->statusMessage("Setting up 'web.config' file...");
				$this->createWebConfig();
			}
		}

		// Load the SilverStripe runtime
		$_SERVER['SCRIPT_FILENAME'] = dirname(realpath($_SERVER['SCRIPT_FILENAME'])) . '/' . FRAMEWORK_NAME . '/main.php';
		chdir(FRAMEWORK_NAME);

		// Rebuild the manifest
		$_GET['flush'] = true;
		// Show errors as if you're in development mode
		$_SESSION['isDev'] = 1;

		$this->statusMessage("Building database schema...");

		require_once 'core/Core.php';

		// Build database
		$con = new Controller();
		$con->pushCurrent();

		global $databaseConfig;
		DB::connect($databaseConfig);

		$dbAdmin = new DatabaseAdmin();
		$dbAdmin->init();

		$dbAdmin->doBuild(true);

		// Create default administrator user and group in database
		// (not using Security::setDefaultAdmin())
		$adminMember = Security::findAnAdministrator();
		$adminMember->Email = $config['admin']['username'];
		$adminMember->Password = $config['admin']['password'];
		$adminMember->PasswordEncryption = Security::get_password_encryption_algorithm();

		try {
			$this->statusMessage('Creating default CMS admin account...');
			$adminMember->write();
		} catch(Exception $e) {
			$this->statusMessage(
				sprintf('Warning: Default CMS admin account could not be created (error: %s)', $e->getMessage())
			);
		}

		// Syncing filesystem (so /assets/Uploads is available instantly, see ticket #2266)
		// show a warning if there was a problem doing so
		try {
			$this->statusMessage('Creating initial filesystem assets...');
			Filesystem::sync();
		} catch(Exception $e) {
			$this->statusMessage(
				sprintf('Warning: Creating initial filesystem assets failed (error: %s)', $e->getMessage())
			);
		}

		$_SESSION['username'] = $config['admin']['username'];
		$_SESSION['password'] = $config['admin']['password'];

		if(!$this->errors) {
			if(isset($_SERVER['HTTP_HOST']) && $this->hasRewritingCapability()) {
				$this->statusMessage("Checking that friendly URLs work...");
				$this->checkRewrite();
			} else {
				$destinationURL = 'index.php/' .
					($this->checkModuleExists('cms') ? 'home/successfullyinstalled?flush=1' : '?flush=1');

				echo <<<HTML
				<li>SilverStripe successfully installed; I am now redirecting you to your SilverStripe site...</li>
				<script>
					setTimeout(function() {
						window.location = "$destinationURL";
					}, 2000);
				</script>
				<noscript>
				<li><a href="$destinationURL">Click here to access your site.</li>
				</noscript>
HTML;
			}
		}

		return $this->errors;
	}

	function writeToFile($filename, $content) {
		$base = $this->getBaseDir();
		$this->statusMessage("Setting up $base$filename");

		if((@$fh = fopen($base . $filename, 'wb')) && fwrite($fh, $content) && fclose($fh)) {
			return true;
		} else {
			$this->error("Couldn't write to file $base$filename");
		}
	}

	function createHtaccess() {
		$start = "### SILVERSTRIPE START ###\n";
		$end = "\n### SILVERSTRIPE END ###";

		$base = dirname($_SERVER['SCRIPT_NAME']);
		if(defined('DIRECTORY_SEPARATOR')) $base = str_replace(DIRECTORY_SEPARATOR, '/', $base);
		else $base = str_replace("\\", '/', $base);

		if($base != '.') $baseClause = "RewriteBase '$base'\n";
		else $baseClause = "";
		$modulePath = FRAMEWORK_NAME;
		$rewrite = <<<TEXT
<Files *.ss>
	Order deny,allow
	Deny from all
	Allow from 127.0.0.1
</Files>

<Files web.config>
	Order deny,allow
	Deny from all
</Files>

ErrorDocument 404 /assets/error-404.html
ErrorDocument 500 /assets/error-500.html

<IfModule mod_alias.c>
	RedirectMatch 403 /silverstripe-cache(/|$)
</IfModule>

<IfModule mod_rewrite.c>
	SetEnv HTTP_MOD_REWRITE On
	RewriteEngine On
	$baseClause
	RewriteCond %{REQUEST_URI} ^(.*)$
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteRule .* $modulePath/main.php?url=%1&%{QUERY_STRING} [L]
</IfModule>
TEXT;

		if(file_exists('.htaccess')) {
			$htaccess = file_get_contents('.htaccess');

			if(strpos($htaccess, '### SILVERSTRIPE START ###') === false && strpos($htaccess, '### SILVERSTRIPE END ###') === false) {
				$htaccess .= "\n### SILVERSTRIPE START ###\n### SILVERSTRIPE END ###\n";
			}

			if(strpos($htaccess, '### SILVERSTRIPE START ###') !== false && strpos($htaccess, '### SILVERSTRIPE END ###') !== false) {
				$start = substr($htaccess, 0, strpos($htaccess, '### SILVERSTRIPE START ###')) . "### SILVERSTRIPE START ###\n";
				$end = "\n" . substr($htaccess, strpos($htaccess, '### SILVERSTRIPE END ###'));
			}
		}

		$this->writeToFile('.htaccess', $start . $rewrite . $end);
	}

	/**
	 * Writes basic configuration to the web.config for IIS
	 * so that rewriting capability can be use.
	 */
	function createWebConfig() {
		$modulePath = FRAMEWORK_NAME;
		$content = <<<TEXT
<?xml version="1.0" encoding="utf-8"?>
<configuration>
	<system.webServer>
		<security>
			<requestFiltering>
				<hiddenSegments applyToWebDAV="false">
					<add segment="silverstripe-cache" />
				</hiddenSegments>
			</requestFiltering>
		</security>
		<rewrite>
			<rules>
				<rule name="SilverStripe Clean URLs" stopProcessing="true">
					<match url="^(.*)$" />
					<conditions>
						<add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
					</conditions>
					<action type="Rewrite" url="$modulePath/main.php?url={R:1}" appendQueryString="true" />
				</rule>
			</rules>
		</rewrite>
	</system.webServer>
</configuration>
TEXT;

		$this->writeToFile('web.config', $content);
	}

	function checkRewrite() {
		$destinationURL = str_replace('install.php', '', $_SERVER['SCRIPT_NAME']) .
			($this->checkModuleExists('cms') ? 'home/successfullyinstalled?flush=1' : '?flush=1');

		echo <<<HTML
<li id="ModRewriteResult">Testing...</li>
<script>
	if(typeof $ == 'undefined') {
		document.getElemenyById('ModeRewriteResult').innerHTML = "I can't run jQuery ajax to set rewriting; I will redirect you to the homepage to see if everything is working.";
		setTimeout(function() {
			window.location = "$destinationURL";
		}, 10000);
	} else {
		$.ajax({
			method: 'get',
			url: 'InstallerTest/testrewrite',
			complete: function(response) {
				if(response.responseText == 'OK') {
					$('#ModRewriteResult').html("Friendly URLs set up successfully; I am now redirecting you to your SilverStripe site...")
					setTimeout(function() {
						window.location = "$destinationURL";
					}, 2000);
				} else {
					$('#ModRewriteResult').html("Friendly URLs are not working. This is most likely because a rewrite module isn't configured "
						+ "correctly on your site. You may need to get your web host or server administrator to do this for you: "
						+ "<ul>"
						+ "<li><strong>mod_rewrite</strong> or other rewrite module is enabled on your web server</li>"
						+ "<li><strong>AllowOverride All</strong> is set for the directory where SilverStripe is installed</li>"
						+ "</ul>");
				}
			}
		});
	}
</script>
<noscript>
	<li><a href="$destinationURL">Click here</a> to check friendly URLs are working. If you get a 404 then something is wrong.</li>
</noscript>
HTML;
	}

	function var_export_array_nokeys($array) {
		$retval = "array(\n";
		foreach($array as $item) {
			$retval .= "\t'";
			$retval .= trim($item);
			$retval .= "',\n";
		}
		$retval .= ")";
		return $retval;
	}

	/**
	 * Show an installation status message.
	 * The output differs depending on whether this is CLI or web based
	 */
	function statusMessage($msg) {
		echo "<li>$msg</li>\n";
		flush();
	}
}
