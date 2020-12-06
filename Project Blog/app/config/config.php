<?php ini_set('display_errors', 1);

// DB Params
define('DB_HOST', 'localhost');
define('DB_PSW', '123456');
define('DB_NAME', 'sharepost');
define('DB_USER', 'root');

// App Root
define(
    // dirname return directory parents, we are in config.php, back twice we get app root
    'APPROOT', dirname(dirname(__FILE__))
);
// URL Root
define(
    'URLROOT', 'http://localhost:8888/sharepost'
);
// Site Name
define(
    'SITENAME', 'Project Blog'
);
// APP Version
define(
    'APPVERSION', '0.1.0'
);

?>
