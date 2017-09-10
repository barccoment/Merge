<?php

/**
 * Project configuration file 
 * 
 * @package PG_Core
 * @subpackage Config
 * @copyright Pilot Group <http://www.pilotgroup.net/>
 * @author Irina Lebedeva <irina@pilotgroup.net>
 * @version $Revision: 68 $ $Date: 2010-01-11 16:02:23 +0300 (Пн, 11 янв 2010) $ $Author: irina $
 */

define("INSTALL_DONE", false);
define("SHOW_CONFIG_READ_ERROR", false);

define("ENVIRONMENT", 'production');

ob_start();
phpinfo(INFO_MODULES);
$s = ob_get_contents();
ob_end_clean();

$s = strip_tags($s,'<h2><th><td>');
$s = preg_replace('/<th[^>]*>([^<]+)<\/th>/',"<info>\\1</info>",$s);
$s = preg_replace('/<td[^>]*>([^<]+)<\/td>/',"<info>\\1</info>",$s);
$vTmp = preg_split('/(<h2>[^<]+<\/h2>)/',$s,-1,PREG_SPLIT_DELIM_CAPTURE);

$vModules = array();

for ($i=1;$i<count($vTmp);$i++) {
	if (preg_match('/<h2>([^<]+)<\/h2>/',$vTmp[$i],$vMat)) {
		$vName = trim($vMat[1]);
		$vTmp2 = explode("\n",$vTmp[$i+1]);
		foreach ($vTmp2 AS $vOne) {
			$vPat = '<info>([^<]+)<\/info>';
			$vPat3 = "/$vPat\s*$vPat\s*$vPat/";
			$vPat2 = "/$vPat\s*$vPat/";
			if (preg_match($vPat3,$vOne,$vMat)) { 
				$vModules[$vName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
			} elseif (preg_match($vPat2,$vOne,$vMat)) { 
				$vModules[$vName][trim($vMat[1])] = trim($vMat[2]);
			}
		}
	}
}

if(!empty($vModules['date']['Default timezone'])){
	date_default_timezone_set($vModules['date']['Default timezone']);
}else{
	date_default_timezone_set(date_default_timezone_get());
}

$dirname = str_replace("\\", "/", dirname(__FILE__))."/";
$site_subfolder = substr(str_replace('index.php', '', filter_input(INPUT_SERVER, 'SCRIPT_NAME')), 1);
if($site_subfolder){
	$site_path = str_replace($site_subfolder , "", $dirname);
}else{
	$site_path = $dirname;
}
$site_path = str_replace("\\", "/", $site_path);
define("SITE_SUBFOLDER", $site_subfolder);
define("SITE_PATH", $site_path);
define("SITE_SERVER", "http://" . $_SERVER["HTTP_HOST"] . "/");
define("MOBILE_SERVER", '');
define("COOKIE_SITE_SERVER", '');

define("DB_HOSTNAME", 'localhost');
define("DB_USERNAME", 'root');
define("DB_PASSWORD", '');
define("DB_DATABASE", 'pgdating');
define("DB_PREFIX", 'pg_');
define("DB_DRIVER", "mysqli");

define("UPLOAD_DIR", "uploads/");
define("DEFAULT_DIR", "default/");
define("DATASOURCE_ICONS_DIR", "datasource_icons/");
define("PATH_TO_IMAGE_MAGIC", "/usr/bin/convert");
