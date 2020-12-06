<?php ini_set('display_errors', 1);

// Load Config
require_once 'config/config.php';

// Load helpers
require_once 'helpers/url_helper.php';
require_once 'helpers/session_helper.php';

// Autoload Core Libraries -- Class name has to match libraries file name
spl_autoload_register(function($className){
    require_once 'libraries/'.$className.'.php';
});



?>
