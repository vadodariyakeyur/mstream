<?php
    include("includes/config.php");
    include("includes/classes/Account.php");
    include("includes/classes/Constants.php");
    $account = new Account($con);
    
    include("includes/handlers/register-handler.php");
    include("includes/handlers/login-handler.php");


    function getInputValue($name){
        if(isset($_POST[$name])){
            echo $_POST[$name];
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MStream</title>
    <link rel="stylesheet" href="assets/css/register.css">

    <link rel="apple-touch-icon" sizes="57x57" href="favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="assets/js/register.js"></script>
</head>
<body>
    <?php 
        if(isset($_POST['registerButton'])){
            echo '<script>$(document).ready(function(){$("#loginForm").hide();$("#registerForm").show();});</script>';
        }else{
            echo '<script>$(document).ready(function(){$("#loginForm").show();$("#registerForm").hide();});</script>';
        }
    ?>
    <div id="background">
        <div id="loginContainer">
            <div id="inputContainer">
                <form action="register.php" method="POST" id="loginForm">
                    <h2>Login To Your Account</h2>
                    <p>
                        <?php echo $account->getError(Constants::$loginFailed); ?>
                        <?php echo $account->getError(Constants::$usernameInvalid); ?>
                        <label for="loginUsername">Username</label>
                        <input type="text" value="<?php getInputValue('loginUsername') ?>" id="loginUsername" name="loginUsername" placeholder="e.g. vKeyur" required>
                    </p>
                    <p>
                        <label for="loginPassword">Password</label>
                        <input type="password" id="loginPassword" placeholder="Your Password" name="loginPassword" required>
                    </p>
                    <button type="submit" name="loginButton">Log In</button>
                    <div class="hasAccountText">
                        <a href="#">
                            <span id="hideLogin">Don't have an account yet? Sign Up</span>
                        </a>
                    </div>
                
                </form>

                <form action="register.php" method="POST" id="registerForm">
                    <h2>Create Your Free Account</h2>
                    <p>
                        <?php echo $account->getError(Constants::$usernameCharacters); ?>
                        <?php echo $account->getError(Constants::$usernameTaken); ?>
                        <?php echo $account->getError(Constants::$usernameInvalid); ?>
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" value="<?php getInputValue('username') ?>" placeholder="e.g. vKeyur" required>
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$firstNameCharacters); ?>
                        <label for="firstName">First Name</label>
                        <input type="text" id="firstName" name="firstName" value="<?php getInputValue('firstName') ?>" placeholder="e.g. Keyur" required>
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$lastNameCharacters); ?>
                        <label for="lastName">Last Name</label>
                        <input type="text" id="lastName" name="lastName" value="<?php getInputValue('lastName') ?>" placeholder="e.g. Vadodariya" required>
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$emailsDoNotMatch); ?>
                        <?php echo $account->getError(Constants::$emailInvalid); ?>
                        <?php echo $account->getError(Constants::$emailTaken); ?>
                        <label for="email">E-mail</label>
                        <input type="email" value="<?php getInputValue('email') ?>" id="email" name="email" placeholder="e.g. keyur.vadodariya@gmail.com" required>
                    </p>
                    <p>
                        <label for="email2">Confirm E-mail</label>
                        <input type="email" id="email2" name="email2" value="<?php getInputValue('email2') ?>" placeholder="e.g. keyur.vadodariya@gmail.com" required>
                    </p>
                    <p>
                        <?php echo $account->getError(Constants::$passwordsDoNotMatch); ?>
                        <?php echo $account->getError(Constants::$passwordsCharacters); ?>
                        <?php echo $account->getError(Constants::$passwordsNotAlphaNumeric); ?>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" required>
                    </p>
                    <p>
                        <label for="password2">Confirm Password</label>
                        <input type="password" id="password2" name="password2" required>
                    </p>
                    <button type="submit" name="registerButton">Sign Up</button>
                    <div class="hasAccountText">
                        <a href="#">
                            <span id="hideRegister">Already have an account? Log In.</span>
                        </a>
                    </div>
                </form>
            </div>
            <div id="loginText">
                <h1>Get great music, right now</h1>
                <h2>Listen to lots of songs for free</h2>
                <ul>
                    <li>Discover music you'll fall in love with</li>
                    <li>Create your playlists</li>
                    <li>Follow artists to keep up to date</li>
                </ul>
            </div>
        </div>
    </div>
</body>
</html>