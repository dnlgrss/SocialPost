<?php ini_set('display_errors', 1);

session_start();



function isLoggedIn(){
    if(isset($_SESSION["user_id"])){
        return true;
    }else{
        return false;
    }
}

// Flash message helper
function flashMsg($flash = '', $message = '', $class = 'alert alert-success') {
    if(!empty($flash)){
        if (!empty($message) && empty($_SESSION[$flash])) {
            if(!empty($_SESSION[$flash. '_class'])){
                unset($_SESSION[$flash. '_class']);
            }
            if(!empty($_SESSION[$flash])){  // This is preventing the alert to still show once we already checked
                unset($_SESSION[$flash]);
            }

            $_SESSION[$flash] = $message;
            $_SESSION[$flash . '_class'] = $class;

        } elseif (empty($message) && !empty($_SESSION[$flash])) {
            $class = !empty($_SESSION[$flash . '_class']) ? $_SESSION[$flash . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$flash] . '</div>';
            unset($_SESSION[$flash]);
            unset($_SESSION[$flash . '_class']);
        }
    }
}





?>
