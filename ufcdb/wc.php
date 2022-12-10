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
    <title><?= $PROJECT_NAME . " | WEIGHT CLASS "?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/buttons.css">
    <style>
    body {
        margin:40px 40px 40px 40px;
        max-width:100%;
        background-repeat: no-repeat;
        background-image: url('img/scale1.jpg');
        background-attachment: fixed;
        background-size: cover;
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
        padding: 8px;
    }
</style>
</head>
<body>
<div class="title"><h1><?= $PROJECT_NAME . " | WEIGHT CLASSES "?></h1></div>
<br>
<a class="logout" href="logout.php"><button class="sub">LOG OUT</button></a>
<div style="margin-top: 30px; display: grid; grid-template-columns: 1fr 1fr 1fr;">
  <div class="qlink" style="background-color: rgb(216, 188, 29);"><a href="champs.php">Champions</a></div>
  <div class="qlink" style="background-color: rgb(153, 37, 37);"><a href="index.php">Home</a></div>
  <div class="qlink" style="background-color: rgb(0, 0, 0);"><a href="fhistory.php">Fight Records</a></div>
</div>

<div class="queries" style="display: block;">
<h2>SEARCH FOR A FIGHTER</h2>
<?php
$select_form = new PhpFormBuilder();
$select_form->set_att("method", "POST");
$select_form->add_input("FighterID to search for", array(
    "type" => "number"
), "search_id");
$select_form->add_input("First and Last Name to search for", array(
    "type" => "text",
    "placeholder" => "Israel Adesanya"
), "search_data");
$select_form->add_input("Submit", array(
    "type" => "submit",
    "value" => "Search"
), "search");
$select_form->build_form();

if (isset($_POST["search"])) {
    $idToFind = htmlspecialchars($_POST["search_id"]);
    $dataToFind = htmlspecialchars($_POST["search_data"]);
    echo "searching...<br>";

    $db = get_mysqli_connection();
    $query = false;

    if (!empty($_POST["search_id"]) && !empty($_POST["search_data"])) {
        echo "searching by fID not FName and LName...<br><br>";
        $query = $db->prepare("select * from FighterWeightClass where fID = ?");
        $query->bind_param("i", $_POST["search_id"]);
    }
    else if (!empty($_POST["search_id"]) && empty($_POST["search_data"])) {
      echo "searching by fID ...<br><br>";
      $query = $db->prepare("select * from FighterWeightClass where fID = ?");
      $query->bind_param("i", $_POST["search_id"]);
    }
    else if (!empty($_POST["search_data"])) {
        echo "searching by FName and LName...<br><br>";
        $query = $db->prepare("call getclass(?)");
        $query->bind_param("s", $_POST["search_data"]);
    }
    if ($query) {
        $query->execute();
        $result = $query->get_result();
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        echo makeTable($rows);
    }
    else{
        echo "Error executing query: " . mysqli_error($db);
    }
}
?>


<br>
<br>

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



