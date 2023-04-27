<?php
namespace Models;

class User extends Database {

    // register the user
    public function register() : void {
        if(isset($_POST['register'])) {
            // grab all the info form the from
            $firstname = $_POST['firstname'];
            $lastname = $_POST['lastname'];
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = hash("sha256", $_POST['password']); // password encryption with sha256
   
            // connect to table and check if the user exists            
            $sql = "SELECT * FROM user WHERE
                    username = :username AND
                    email = :email";
            $query = $this->bdd->prepare($sql); 
            $query->bindParam(':username', $username, \PDO::PARAM_STR);
            $query->bindParam(':email', $email, \PDO::PARAM_STR);
            $query->execute(); 
            $user = $query->fetch();
            if ($user) {
                // username already exists: give error
                header('Location: index.php?road=goRegister');
                $_SESSION['login'] = "<div class='error'> It seems that you already have an account with use! Please log in. </div>";
            } 
            else {
                // username does not exist, add it to database
                // prepare SQL query to save the data into database
                $sql = "INSERT INTO user SET
                        firstname = :firstname,
                        lastname = :lastname,
                        username = :username,
                        email = :email,
                        password = :password
                        ";
                        
                $query = $this->bdd->prepare($sql);
                $query->bindParam(':username', $username, \PDO::PARAM_STR);
                $query->bindParam(':firstname', $firstname, \PDO::PARAM_STR);
                $query->bindParam(':lastname', $lastname, \PDO::PARAM_STR);
                $query->bindParam(':email', $email, \PDO::PARAM_STR);
                $query->bindParam(':password', $password, \PDO::PARAM_STR);
                $res = $query->execute();
                $query->closeCursor(); 
        
                    
                // check if data inserted or not and display the message
                if($res==TRUE) {
                    $_SESSION['auth'] = true;
                    $_SESSION['user'] = $username;
                    $_SESSION['login'] = "<div class='success'> You are logged in successfully! </div>";
                    //page redirection
                    header('Location: index.php?road=home');
                }
                else {
                    $_SESSION['login'] = "<div class='error'> Registration failed! Please try again. </div>";
                    //page redirection
                    header('Location: index.php?road=goRegister');
                }
            }
        }
        else {
            $_SESSION['login'] = "<div class='error'> Registration failed! Please try again. </div>";
            //page redirection
            header('Location: index.php?road=goRegister'); 
        }
    }

    // login the user
    public function login() : void {
        if(isset($_POST['login'])) {
            // get data from login form
            $username = $_POST['username'];
            $password = hash("sha256", $_POST['password']);
        
            // SQL to check if the user with usuername and password exists or not
            $sql = "SELECT * FROM user WHERE username=:username  AND password=:password";
            $query = $this->bdd->prepare($sql);
            $query->bindParam(':username', $username, \PDO::PARAM_STR);
            $query->bindParam(':password', $password, \PDO::PARAM_STR);
            $res = $query->execute();
            
            // count rows to check if the user exists or not
            $count = $query->rowCount();
            
            if ($count==1) {
                // user available and login success
                $_SESSION['auth'] = true;
                $_SESSION['login'] = "<div class='success'> You are logged in successfully! </div>";
                $_SESSION['user'] = $username;
                if(isset($_SESSION["cart"])) {
                    unset($_SESSION["cart"]);
                }
                //redirect to homepage
                header('Location: index.php?road=home');
            }
            else {
                // user not available and login fail
                $_SESSION['login'] = "<div class='error'> Login Failed. Please try again! </div>";
                // redirect to homepage
                header('Location: index.php?road=goLogin');
            }
        }
        else {
            // user not available and login fail
            $_SESSION['login'] = "<div class='error'> Login Failed. Please try again! </div>";
            // redirect to homepage
            header('Location: index.php?road=goLogin');
        }
    }
    
    // logout the user and destroy the session
    public function logout() : void {
        session_destroy(); 
        header('Location: index.php?road=home'); // redirect to home 
    }

}
