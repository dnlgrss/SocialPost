<?php ini_set('display_errors', 1);

/*
* Base Controller
* Loads the models and views
*/

class Controller{
    //Load Model
    public function model($model){
        // Require model file
        require_once '../app/models/'.$model.'.php';

        // Instantiate the model
        return new $model();
    }

    // Load View
    public function view($view, $data = []){
        // Check for view file
        if(file_exists('../app/views/'.$view.'.php')){
            require_once '../app/views/'.$view.'.php';
        }else{
            // View does not exist
            die('View does not exist');
        }
    }
}

?>
