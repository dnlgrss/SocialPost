<?php ini_set('display_errors', 1);

class Posts extends Controller{

    public function __construct(){
        // I don't want people to access posts pages if there not logged in
        if(!isLoggedIn()){
            redirect('users/login');
        }

        $this->postModel = $this->model('Post');
        $this->userModel = $this->model('User');
    }

    public function index(){
        // Get posts
        $posts = $this->postModel->getPosts();

        $data = [
            'posts' => $posts
        ];

        $this->view('posts/index', $data);
    }

    public function add(){
        if($_SERVER["REQUEST_METHOD"] == 'POST'){
            // Sanitaze post
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'title' => trim($_POST["title"]),
                'body' => trim($_POST["body"]),
                'user_id' => $_SESSION["user_id"],
                'title_err' => '',
                'body_err' => ''
            ];

            // Validate title
            if(empty($data['title'])){
                $data['title_err'] = 'Please enter title';
            }

            // Validate body
            if(empty($data['body'])){
                $data['body_err'] = 'Please enter text';
            }

            // Make sure no errors
            if(empty($data['title_err']) && empty($data['body_err'])){
                // Validated
                if($this->postModel->addPost($data)){
                    flashMsg('post_msg', 'Your post was added');
                    redirect('posts');
                }else{
                    die('Something went wrong');
                }
            }else{
                // Load view with errors
                $this->view('posts/add', $data);
            }

        }else{
            $data = [
                'title' => '',
                'body' => ''
            ];

            $this->view('posts/add', $data);
        }
    }


    public function edit($id){
        if($_SERVER["REQUEST_METHOD"] == 'POST'){
            // Sanitaze post
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'title' => trim($_POST["title"]),
                'body' => trim($_POST["body"]),
                'user_id' => $_SESSION["user_id"],
                'title_err' => '',
                'body_err' => ''
            ];

            // Validate title
            if(empty($data['title'])){
                $data['title_err'] = 'Please enter title';
            }

            // Validate body
            if(empty($data['body'])){
                $data['body_err'] = 'Please enter text';
            }

            // Make sure no errors
            if(empty($data['title_err']) && empty($data['body_err'])){
                // Validated
                if($this->postModel->updatePost($data)){
                    flashMsg('post_msg', 'Your post has been updated');
                    redirect('posts');
                }else{
                    die('Something went wrong');
                }
            }else{
                // Load view with errors
                $this->view('posts/edit', $data);
            }

        }else{
            $post = $this->postModel->getPostById($id);

            // Check for post owner we don't want anybody to be able to edit
            if($post->user_id != $_SESSION["user_id"]){
                redirect('posts');
            }

            $data = [
                'id' => $id,
                'title' => $post->title,
                'body' => $post->body
            ];

            $this->view('posts/edit', $data);
        }
    }

    public function show($id){
        $post = $this->postModel->getPostById($id);
        $user = $this->userModel->getUserById($post->user_id);

        $data = [
            'post' => $post,
            'user' => $user
        ];

        $this->view('posts/show', $data);
    }

    public function delete($id){
        if($_SERVER["REQUEST_METHOD"] == 'POST'){
            $post = $this->postModel->getPostById($id);

            // Check for post owner we don't want anybady to be able to edit
            if($post->user_id != $_SESSION["user_id"]){
                redirect('posts');
            }

            if($this->postModel->deletePost($id)){
                flashMsg('post_msg', 'Post Deleted');
                redirect('posts');
            }else{
                die('Something went wrong');
            }
        }else{
            redirect('posts');
        }
    }
}
?>
