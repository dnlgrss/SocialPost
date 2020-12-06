<?php ini_set('display_errors', 1);

// Needs to extend Controller for pages conotroller because it handles to load models and view needed
class Pages extends Controller{
    public function __construct(){

    }


    public function index(){

        if(isLoggedIn()){
            redirect('posts');
        }

        $data = [
            // Set the var
            'title' => SITENAME,
            'description' => 'Simple social network built on the MVC PHP Framework'
        ];

        // Load data of Post.php and shoot them into view (*index)
        $this->view('pages/index', $data);
    }

    public function about(){
        $data = [
            'title' => 'About Us',
            'description' => 'App to share posts with other users'
        ];

        // Load data of Post.php and shoot them into view (*about)
        $this->view('pages/about', $data);
    }

}
?>
