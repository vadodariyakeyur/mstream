<?php
    class Account
    {
        private $errorArray;
        private $con;

        public function __construct($con){
            $this->errorArray = array();
            $this->con = $con;
        }

        public function login($un,$pw){

            $this->validateUsername($un);

            if(preg_match('/[^A-Za-z0-9]/',$un)){
                array_push($this->errorArray,Constants::$usernameInvalid);
                return;
            }

            $pw = md5($pw);

            $query = mysqli_query($this->con,"SELECT * FROM users WHERE username='$un' AND password='$pw'");

            if(mysqli_num_rows($query) == 1){
                return true;
            }else{
                array_push($this->errorArray,Constants::$loginFailed);
                return false;
            }
        }

        public function register($un,$fn,$ln,$em,$em2,$pw,$pw2){
            $this->validateUsername($un);
            $this->validateFirstname($fn);
            $this->validateLastname($ln);
            $this->validateEmails($em,$em2);
            $this->validatePasswords($pw,$pw2);

            if(empty($this->errorArray)){
                //TODO: Insert Into DB
                return $this->insertUserDetails($un,$fn,$ln,$em,$pw);
            }else{
                return false;
            }
        }

        private function insertUserDetails($un,$fn,$ln,$em,$pw){
            $encryptedPw = md5($pw);
            $profilePic = "assets/images/profile-pics/profile_pic.jpg";
            $date = date("Y-m_d");

            $result = mysqli_query($this->con,"INSERT INTO users VALUES('','$un','$fn','$ln','$em','$encryptedPw','$date','$profilePic')");

            return $result;
        }

        public function getError($error){
            if(!in_array($error,$this->errorArray)){
                $error = "";
            }
            return "<span class='errorMessage'>$error</span>";
        }

        private function validateUsername($un){
            
            if(strlen($un) > 25 || strlen($un) < 5){
                array_push($this->errorArray,Constants::$usernameCharacters);
                return;
            }

            if(preg_match('/[^A-Za-z0-9]/',$un)){
                array_push($this->errorArray,Constants::$usernameInvalid);
                return;
            }

            //TODO: Check if username already exists
            $checkUsernameQuery = mysqli_query($this->con,"SELECT username FROM users WHERE username='$un'");

            if(mysqli_num_rows($checkUsernameQuery)!=0){
                array_push($this->errorArray,Constants::$usernameTaken);
            }
        }
        
        private function validateFirstname($fn){
            if(strlen($fn) > 25 || strlen($fn) < 2){
                array_push($this->errorArray,Constants::$firstNameCharacters);
                return;
            }
        }
        
        private function validateLastname($ln){
            if(strlen($ln) > 25 || strlen($ln) < 2){
                array_push($this->errorArray,Constants::$lastNameCharacters);
                return;
            }
        }
        
        private function validateEmails($em,$em2){
            if ($em != $em2) {
                array_push($this->errorArray,Constants::$emailsDoNotMatch);
                return;
            }

            if(!filter_var($em,FILTER_VALIDATE_EMAIL)){
                array_push($this->errorArray,Constants::$emailInvalid);
                return;
            }

            //TODO: Check whether email hasn't already used
            $checkEmailQuery = mysqli_query($this->con,"SELECT email FROM users WHERE email='$em'");

            if(mysqli_num_rows($checkEmailQuery)!=0){
                array_push($this->errorArray,Constants::$emailTaken);
            }
        }
        
        private function validatePasswords($pw,$pw2){
            if ($pw != $pw2) {
                array_push($this->errorArray,Constants::$passwordsDoNotMatch);
                return;
            }

            if(preg_match('/[^A-Za-z0-9]/',$pw)){
                array_push($this->errorArray,Constants::$passwordsNotAlphaNumeric);
                return;
            }

            if(strlen($pw) > 30 || strlen($pw) < 8){
                array_push($this->errorArray,Constants::$passwordsCharacters);
                return;
            }
        }
    }
?>