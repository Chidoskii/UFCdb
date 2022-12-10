<?php
require_once("config.php");
if(!isset($_SESSION["is_emp"]) && !isset($_SESSION["is_user"])){
  require_once("index.php");
}
?>

<?php
if (!empty($_SESSION["affected_rows"])) {
    echo "Deleted " . $_SESSION["affected_rows"] . " rows";
    unset($_SESSION["affected_rows"]);
}
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME . " | CHAMPIONS "?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/buttons.css">
    <style>
    body {
        margin:40px 40px 40px 40px;
        max-width:100%;
        background-image: url('img/title.jpg');
        background-attachment: fixed;
        color: gold;
        }
    .queries {
        font-family: Roboto, Arial;
        padding: 10px 10px 1px 10px;
        background: rgba(10, 12, 15, 0.5);
        display: inline-block;
    }
    .title {
        font-family: Roboto, Arial;
        margin: auto;
        padding: 1px 1px 1px 1px;
        background: rgba(10, 12, 15, 0.5);
        display: block;
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
   td{
        padding: 8px;
    }
   

</style>
</head>
<body>
<div class="title"><h1><?= $PROJECT_NAME . " | CHAMPIONS "?></h1></div>
<br>
<a class="logout" href="logout.php"><button class="sub">LOG OUT</button></a>
<br>
<div style="margin-top: 30px; display: grid; grid-template-columns: 1fr 1fr 1fr;">
  <div class="qlink" style="background-color: rgb(216, 188, 29);"><a href="index.php">Home</a></div>
  <div class="qlink" style="background-color: rgb(153, 37, 37);"><a href="wc.php">Weight Class</a></div>
  <div class="qlink" style="background-color: rgb(0, 0, 0);"><a href="fhistory.php">Fight Records</a></div>
</div>

<div class="queries" style="display: block;">
<h2>CURRENT CHAMPIONS</h2>
<?php
$db = get_mysqli_connection();
$query = false;
$query = $db->prepare("call getchamps()");

if ($query) {
  $query->execute();
  $result = $query->get_result();
  $rows = $result->fetch_all(MYSQLI_ASSOC);
  echo makeTable($rows);
}
else{
  echo "Error executing query: " . mysqli_error($db);
}
?>

</div>



