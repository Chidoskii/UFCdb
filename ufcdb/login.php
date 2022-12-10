<?php
require_once("config.php");

function get_emp($email) {
    $db = get_mysqli_connection();
    $query = $db->prepare("SELECT * FROM Employees  WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);

    
    return $results[0];

}

function get_user($email) {
    $db = get_mysqli_connection();
    $query = $db->prepare("SELECT * FROM Users  WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();
    $results = $result->fetch_all(MYSQLI_ASSOC);

    
    return $results[0];

}

if (isset($_POST["Login"])) {
    $login_email = $_POST["login_email"];
    $login_password = $_POST["login_password"];

    if (strlen($login_email) == 0 || strlen($login_password) == 0) {
        $_SESSION["login_error"] = "email and Password cannot be empty.";
    }
    else {
        $db = get_mysqli_connection();
        $query = $db->prepare("SELECT Password FROM Employees WHERE email = ?");
        $query->bind_param("s",$login_email);
        $query->execute();
        $result = $query->get_result();
        $results = $result->fetch_all(MYSQLI_ASSOC);

        if (count($results) > 0) {
            $hash = $results[0]["Password"];

            if (password_verify($login_password, $hash)) {
                $_SESSION["logged_in"] = true;

                $emp = get_emp($login_email);

                if (count($emp) > 0){
                    $_SESSION["is_emp"] = true;
                    $_SESSION["get_emp"] = $emp;
                    $_SESSION["is_user"] = false;
                }
                else {
                    $_SESSION["is_emp"] = false;
                }

                header("Location: index.php");
            }
            else {
                $_SESSION["login_error"] = "Invalid username and password combination.";
            }
        }
        $query = $db->prepare("SELECT Password FROM Users WHERE email = ?");
        $query->bind_param("s",$login_email);
        $query->execute();
        $result = $query->get_result();
        $results = $result->fetch_all(MYSQLI_ASSOC);
        if (count($results) > 0) {
            $hash = $results[0]["Password"];

            if (password_verify($login_password, $hash)) {
                $_SESSION["logged_in"] = true;
                $_SESSION["login_email"] = $login_email;

                $user = get_user($login_email);

                if (count($user) > 0){
                    $_SESSION["is_user"] = true;
                    $_SESSION["get_user"] = $user;
                    
                }
                else {
                    $_SESSION["is_user"] = false;
                }

                header("Location: index.php");
            }
            else {
                $_SESSION["login_error"] = "Invalid username and password combination.";
            }
        }
        else {
            $_SESSION["login_error"] = "Invalid username and password combination.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME . " | Login" ?></title>
    <link rel="stylesheet" href="styles/style.css">
    
    <style>
    body {
        background-image: url('img/vitokick.jpg');
        background-repeat: no-repeat;
        background-size: cover;
        background-attachment: fixed;
        color: white;}
    .login {
        font-family: Roboto, Arial;
        padding: 10px 10px 1px 10px;
        background: rgba(10, 12, 15, 0.5);
        width: 275px;
    }
    .tenor-gif-embed {
        margin: auto;
        width: 50%;
        padding: 10px;
    }
    button, html input[type=button], input[type=reset], input[type=submit] {
    -webkit-appearance: button;
    cursor: pointer;
    color: white;
    background-color: rgba(77, 130, 255, 0.87);
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 8px;
    margin-top: 8px;
    margin-bottom: 8px;
    transition: opacity 0.3s;
    padding-bottom: 1px;
    padding-top: 1px;
    padding-left: 5px;
    padding-right: 5px;
    vertical-align: top;
}
  button, input, optgroup, select, textarea {
    color: black;
    font: inherit;
    margin: 0;
}
button:hover, html input[type=button]:hover, input[type=reset]:hover, input[type=submit]:hover {
      opacity: 0.6;
    }
button:active, html input[type=button]:active, input[type=reset]:active, input[type=submit]:active {
      opacity: 0.3;
    }
</style>
</head>
<body>
<h1><?= $PROJECT_NAME ?></h1>
<h2>Hello! Please Login.</h2>


<div class="login">
<?php 
$login_form = new PhpFormBuilder();
$login_form->set_att("method", "POST");
$login_form->add_input("email", array(
    "type" => "text",
    "required" => true
), "login_email");
$login_form->add_input("Password", array(
    "type" => "password",
    "required" => true
), "login_password");
$login_form->add_input("Login", array(
    "type" => "submit",
    "value" => "Login"
), "Login");
$login_form->build_form();

if (isset($_SESSION["login_error"])) {
    echo $_SESSION["login_error"] . "<br>";
    unset($_SESSION["login_error"]);
}
?>
</div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<div class="tenor-gif-embed" data-postid="19820149" data-share-method="host" data-aspect-ratio="1.75824" data-width="65%" >
    <a href="https://tenor.com/view/joaquin-buckley-ufc-knockout-kermit-gif-19820149">Joaquin Buckley Ufc GIF</a>
    from <a href="https://tenor.com/search/joaquin+buckley-gifs">Joaquin Buckley GIFs</a>
</div> 
<script type="text/javascript" async src="https://tenor.com/embed.js"></script>

</body>
</html>
