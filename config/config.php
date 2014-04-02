<?php
error_reporting(E_ALL ^ (E_NOTICE | E_WARNING | E_DEPRECATED));
define('DOMAIN','http://ncconfig.yourdomain.com/');
define('ROOT_PATH','/path/to/your/code/');
define('SMARTY_PATH',ROOT_PATH.'/html/smarty/Smarty.class.php');
define('LOG_PATH',ROOT_PATH.'log/');
define('CSS_PATH',DOMAIN.'css/');
define('JS_PATH',DOMAIN.'js/');
define('IMG_PATH',DOMAIN.'images/');
define('DEFAULT_ROLE','READ_ONLY');
define('DEFAULT_PASSWORD',md5('password'));
define('TIME_OUT',3600);
define('BASE_CRON_COMMAND','/usr/local/php/bin/php /path/to/your/code/mycronic.php');
define('CRON_FILE_PATH','//path/to/your/code/files/crontab/');
define('CRON_SVN_PATH','http://svn/path');
define('SVN_AUTH_DEFAULT_USERNAME','SVN_USERNAME');
define('SVN_AUTH_DEFAULT_PASSWORD','SVN_PASSWORD');
define('CRON_FILE_BACKUP_PATH','/path/to/your/code/cron/backup');
?>
