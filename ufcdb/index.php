<?php
require_once("config.php");

if(empty($_SESSION["logged_in"])){
    header("location: login.php");
}

if($_SESSION["logged_in"] == false){
    header("location: login.php");
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/buttons.css">
    <style>
    body {
        margin:40px 40px 40px 40px;
        max-width:100%;
        background-image: url('img/forest.jpg');
        background-attachment: fixed;
        color: gold;
        }
    .queries {
        font-family: Roboto, Arial;
        padding: 10px 10px 1px 10px;
        background: rgba(10, 12, 15, 0.5);
        display: inline-block;

    }
    .qlink {
        color:white;
        text-align: center;
        font-family: Roboto, Arial;
        font-size: 30px;
        padding: 0px 1px 1px 10px;
    }
    h1 {
        text-align: center;
        font-family: Roboto, Arial;
    }
    h2, h3 {
        text-align: left;
        font-family: Roboto, Arial;
    }
    a:link {
        color: white;
        text-decoration: none;
    }
    /* visited link */
    a:visited {
    color: green;
    text-decoration: none;
    }
    /* mouse over link */
    a:hover {
    color: hotpink;
    }
    /* selected link */
    a:active {
    color: blue;
    text-decoration: none;
    }
    a.logout{
    text-align: right;
    display: block;
    float: right;
   }
   button, html input[type=button], input[type=reset], input[type=submit] {
    -webkit-appearance: button;
    cursor: pointer;
    color: white;
    background-color: rgb(201, 0, 0);
    border: none;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 8px;
    transition: opacity 0.3s;
    padding-bottom: 1px;
    padding-top: 1px;
    padding-left: 5px;
    padding-right: 5px;
    vertical-align: top;
}
button:active, html input[type=button]:active, input[type=reset]:active, input[type=submit]:active {
      opacity: 0.3;
    }
    td{
        padding: 3px;
    }
</style>
</head>
<body>
<h1><?= $PROJECT_NAME?></h1>

<a class="logout" href="logout.php"><button class="sub">LOG OUT</button></a>
<h2>Welcome back!</h2>
    <?php
      if(isset($_SESSION["is_emp"]) && ($_SESSION["is_emp"])){
        require_once("employee.php");
      }
      else{
        require_once("user.php");
      }
    ?>
    



