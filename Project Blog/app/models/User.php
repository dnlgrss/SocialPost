<?php ini_set('display_errors', 1);

class User{
    private $db;

    public function __construct(){
        $this->db = new Database;
    }

    // Register User
    public function registerUser($data){
        $this->db->query('INSERT INTO users (name, email, password)
                        VALUES (:name, :email, :password)');

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        //Execute
        if($this->db->execute()){
            return true;
        }else{
            return false;
        }
    }

    public function login($email, $password){
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        if($row){ // We need to check if row exist if you insert the wrong email query will not find any result and it will throw an error
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)){
                return $row;
            }else{
                return false;
            }
        }
    }


    // Find user by email
    public function findUserByEmail($email){
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();

        // Check row
        if($this->db->rowCount() > 0){
            return true;
        }else{
            return false;
        }
    }

    // Get User by id
    public function getUserById($id){
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }
}

?>
