<?php ini_set('display_errors', 1);

class Users extends Controller {
    public function __construct() {
        $this->userModel = $this->model('User');
    }

    public function register() {
        // Check for post
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            // *** Process the from

            // Sanitaze POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'name' => trim($_POST["name"]),
                'email' => trim($_POST["email"]),
                'password' => trim($_POST["password"]),
                'confirm_password' => trim($_POST["confirm_password"]),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];

            // Email Validation
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter Email';
            } else {
                // Check email
                if ($this->userModel->findUserByEmail($data['email'])) {
                    $data['email_err'] = 'Email is already taken';
                }
            }

            // Name Validation
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter Name';
            };

            // Password Validation
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            } elseif (strlen($data['password']) < 6) {
                $data['password_err'] = 'Please too short, at least 6 character!';
            };

            // Confrim password Validation
            if (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Password do not match';
                }
            };

            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['name_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Validated
                // Hash Password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register user
                if ($this->userModel->registerUser($data)) {
                    flashMsg('register_success', 'You are registered and can now login');
                    redirect('users/login');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/register', $data);
            }
        } else {
            // Init data
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
            ];

            //Load the form
            $this->view('users/register', $data);
        }
    }


    public function login() {
        // Check for post
        if ($_SERVER["REQUEST_METHOD"] == 'POST') {
            // Process the from
            // Sanitaze POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'email' => trim($_POST["email"]),
                'password' => trim($_POST["password"]),
                'email_err' => '',
                'password_err' => '',
            ];

            // Email Validation
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter Email';
            };

            // Password Validation
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }


            // Make sure errors are empty
            if (empty($data['email_err']) && empty($data['password_err'])) {
                // Validation
                //Check for user/email
                if ($this->userModel->findUserByEmail($data['email'])) {
                    // User found
                    $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                    if ($loggedInUser) {
                        // Create session - User logged in
                        $this->createUserSession($loggedInUser);
                    } else {
                        flashMsg('no_user_password', 'Password incorrect', 'alert alert-danger');
                        $this->view('users/login', $data);
                        // $data['password_err'] = 'Password incorrect';
                        // $this->view('users/login', $data);
                    }
                } else {
                    // User not found
                    flashMsg('no_user_login', 'No user found', 'alert alert-danger');
                    $this->view('users/login', $data);
                    //$data['email_err'] = 'No user found';
                    //$this->view('users/login', $data);
                }



                // Set and check login user
                // $loggedInUser = $this->userModel->login($data['email'], $data['password']);

                // if($loggedInUser){
                //     // Create session
                //     die('Success');
                // }else{
                //     flashMsg('no_user_password', 'Password incorrect', 'alert alert-danger');
                //     $this->view('users/login', $data);
                //     // $data['password_err'] = 'Password incorrect';
                //     // $this->view('users/login', $data);
                // }

            } else {
                // Load view with errors
                $this->view('users/login', $data);
            }
        } else {
            // Init data
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => '',
            ];

            //Load the form
            $this->view('users/login', $data);
        }
    }

    public function createUserSession($loggedInUser){
        $_SESSION["user_id"] = $loggedInUser->id;
        $_SESSION["user_email"] = $loggedInUser->email;
        $_SESSION["user_name"] = $loggedInUser->name;
        redirect('posts');
    }

    public function logout(){
        unset($_SESSION["user_id"]);
        unset($_SESSION["user_email"]);
        unset($_SESSION["user_name"]);
        session_destroy();
        redirect('pages/index');
    }


}
