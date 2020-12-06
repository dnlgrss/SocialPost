<?php ini_set('display_errors', 1);

/*
*  App Core Class
*  (creates URL & Load core controller)
*  URL FORMAT - /controller/method/params
*/

class Core{
    protected $currentController = 'Pages'; // It has to exist in controllers folder
    protected $currentMethod = 'Index'; // It has to be an actual method in Pages class
    protected $params = [];

    public function __construct(){
        //print_r($this->getUrl());

        // Get array of current url
        $url = $this->getUrl();

        // Look if we have a controllers that corrispond to the first value in url -- URL FORMAT - /controller/method/params
        // ucwords - Upper case (Controller will start with upper case)
        // if we put something in url
        if(isset($url[0])){
            // if that something is actually a controller
            if(file_exists('../app/controllers/'.ucwords($url[0]).'.php')){
            // If exists, set as controller
            $this->currentController = ucwords($url[0]);
            // Unset 0 index
            unset($url[0]);
            }
        };

        // Require that controller we just checked or if it doesnt exists it requires the default (protected $currentConotroller = 'Pages')
        require_once '../app/controllers/'. $this->currentController.'.php';

        // Instantiate controller class we just checked (it has to exist.. php file, class and of course construct to enable access))
        $this->currentController = new $this->currentController;

        // Check for second part of url -- URL FORMAT - /controller/method/params
        if(isset($url[1])){
            // Check to see if method exist & controller
            // method_exists — Checks if the class method exists -- method_exists($object, $method_name)
            // EXAMPLE 'pages/about' -- it checks in controller Pages if there's a method called about
            if(method_exists($this->currentController, $url[1])){
                $this->currentMethod = $url[1];
                // Unset 1 index
                unset($url[1]);
            }
        }



        // Get params
        // We want and array of everything in url updated so far (controller & method wont be there because we unset it, so everything that comes after will be in the following array)
        $this->params = $url ? array_values($url) : [];

        // Call a callback with array of params
        // call_user_func_array — Call a callback with an array of parameters -- You use it when you dont know beforehand what funciton and paramaters you will call
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

    }

    // Get current url and place it in array devided by '/'   -   exemple/example2/bobo => [example, example2, bobo]
    public function getUrl(){
        if(isset($_GET["url"])){
            // rtrim — Strip whitespace (or other characters) from the end of a string - rtrim($string, $characterToTrim)
            $url = rtrim($_GET["url"], '/');
            // removes all illegal URL characters from a string
            $url = filter_var($url, FILTER_SANITIZE_URL);
            // explode — Split a string by a string, returns array of strings - explode($delimitatore, $string)
            $url = explode('/', $url);
            return $url;
        }
    }
}



?>
