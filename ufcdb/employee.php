<?php
require_once("config.php");

if (empty($_SESSION["logged_in"])) {
    header("Location: login.php");
}

if ($_SESSION["is_emp"] == false) {
    header("Location: index.php");
}


if(!isset($_SESSION["is_emp"])){
  require_once("index.php");
}
?>
<h3>
<?php
echo "Our Hardest working employee, " . $_SESSION["get_emp"]["first_name"] . " " . $_SESSION["get_emp"]["last_name"] . "!";
?>
</h3>


<?php
if (!empty($_SESSION["affected_rows"])) {
    echo "Deleted " . $_SESSION["affected_rows"] . " rows";
    unset($_SESSION["affected_rows"]);
}
?>






<div style="margin-top: 30px; display: grid; grid-template-columns: 1fr 1fr 1fr;">
  <div class="qlink" style="background-color: rgb(216, 188, 29);"><a href="champs.php">Champions</a></div>
  <div class="qlink" style="background-color: rgb(153, 37, 37);"><a href="wc.php">Weight Class</a></div>
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
$select_form->add_input("Name to search for", array(
    "type" => "text",
    "placeholder" => "Israel Adesanya"
), "search_data");
$select_form->add_input("Submit", array(
    "type" => "submit",
    "value" => "Search"
), "search");
$select_form->build_form();

if (isset($_POST["search"])) {
    echo "searching...<br>";

    $db = get_mysqli_connection();
    $query = false;

    if (!empty($_POST["search_id"])&& empty($_POST["search_data"])) {
        echo "searching by FighterID...";
        $query = $db->prepare("call idfighter(?)");
        $query->bind_param("i", $_POST["search_id"]);
    }
    if (!empty($_POST["search_id"])&& !empty($_POST["search_data"])) {
      echo "searching by FighterID not FName and LName...";
      $query = $db->prepare("call idfighter(?)");
      $query->bind_param("i", $_POST["search_id"]);
  }
    else if (!empty($_POST["search_data"])) {
        echo "searching by FName and LName...";
        $query = $db->prepare("select * from Fighter where concat(FName, ' ', LName) = ?");
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


<h2>ADD A FIGHTER</h2>
<?php
$insert_form = new PhpFormBuilder();
$insert_form->set_att("method", "POST");
$insert_form->add_input("First Name", array(
    "type" => "text",
    "placeholder" => "George"
), "insert_data");
$insert_form->add_input("Last Name", array(
    "type" => "text",
    "placeholder" => "Washington"
), "insert_data1");
$insert_form->add_input("Insert", array(
    "type" => "submit",
    "value" => "Insert"
), "insert");
$insert_form->build_form();

if (isset($_POST["insert"]) && !empty($_POST["insert_data"]) && !empty($_POST["insert_data1"])) {
    $dataToInsert = htmlspecialchars($_POST["insert_data"]);
    $dataToInsert1 = htmlspecialchars($_POST["insert_data1"]);
    echo "inserting $dataToInsert $dataToInsert1 ...";

    $db = get_mysqli_connection();
    $query = $db->prepare("insert into Fighter(FName, LName) values (?, ?)");
    $query->bind_param("ss",$dataToInsert,$dataToInsert1);
    if ($query->execute()) {    
        echo "Fighter added";
    }
    else {
        echo "Error inserting: " . mysqli_error($db);
    }
}
?>


<h2>EDIT A FIGHTER</h2>
<?php
$update_form = new PhpFormBuilder();
$update_form->set_att("method", "POST");
$update_form->add_input("FighterID to update data for", array(
    "type" => "number"
), "update_gender");
$update_form->add_input("Update Gender", array(
    "type" => "text",
    "placeholder" => "M/F"
), "update_data");
$update_form->add_input("Update", array(
    "type" => "submit",
    "value" => "Update"
), "update");
$update_form->build_form();

if (isset($_POST["update"]) && !empty($_POST["update_data"]) && !empty($_POST["update_gender"])) {
    $dataToUpdate = htmlspecialchars($_POST["update_data"]);
    $idToUpdate = htmlspecialchars($_POST["update_gender"]);
    echo "updating...<br>";

    $db = get_mysqli_connection();
    $query = $db->prepare("update Fighter set Gender = ? where FighterID = ?");
    $query->bind_param("si", $dataToUpdate, $idToUpdate);
    if ($query->execute()) {    
        echo "$idToUpdate updated<br>";
    }
    else {
        echo "Error updating: " . mysqli_error($db);
    }
}
?>


<?php
$update_form = new PhpFormBuilder();
$update_form->set_att("method", "POST");
$update_form->add_input("FighterID to update data for", array(
    "type" => "number"
), "update_id");
$update_form->add_input("Update DOB", array(
    "type" => "text",
    "placeholder" => "1997-02-13"
), "update_dob");
$update_form->add_input("Update", array(
    "type" => "submit",
    "value" => "Update"
), "update");
$update_form->build_form();

if (isset($_POST["update"]) && !empty($_POST["update_id"]) && !empty($_POST["update_dob"])) {
    $dobToUpdate = htmlspecialchars($_POST["update_dob"]);
    $idToUpdate = htmlspecialchars($_POST["update_id"]);
    echo "updating Fighter #$idToUpdate DOB to $dobToUpdate<br>";

    $db = get_mysqli_connection();
    $query = $db->prepare("update Fighter set DOB = ? where FighterID = ?");
    $query->bind_param("si", $dobToUpdate, $idToUpdate);
    if ($query->execute()) {    
        echo "complete.<br>";
    }
    else {
        echo "Error updating: " . mysqli_error($db);
    }
}
?>

<h2>ADD A BOUT</h2>
<?php
$insert_form = new PhpFormBuilder();
$insert_form->set_att("method", "POST");
$insert_form->add_input("FirstFighterID", array(
    "type" => "number",
), "insert_ffID");
$insert_form->add_input("SecondFighterID", array(
    "type" => "number",
), "insert_sfID");
$insert_form->add_input("Event Date", array(
    "type" => "text",
    "placeholder" => "1997-02-13"
), "insert_date");
$insert_form->add_input("City", array(
    "type" => "text",
    "placeholder" => "Las Vegas"
), "insert_city");
$insert_form->add_input("Country", array(
    "type" => "text",
    "placeholder" => "USA"
), "insert_country");
$insert_form->add_input("Results: (-1 for NC, 0 for Draw, 1 for 1st Fighter, 2 for 2nd Fighter)", array(
    "type" => "number",
), "insert_outcome");
$insert_form->add_input("Insert", array(
    "type" => "submit",
    "value" => "Insert"
), "ADD");
$insert_form->build_form();

if (isset($_POST["ADD"])) {
    $fighter1 = htmlspecialchars($_POST["insert_ffID"]);
    $fighter2 = htmlspecialchars($_POST["insert_sfID"]);
    $dateToInsert = htmlspecialchars($_POST["insert_date"]);
    $arenacity = htmlspecialchars($_POST["insert_city"]);
    $arenacountry = htmlspecialchars($_POST["insert_country"]);
    $outcome = htmlspecialchars($_POST["insert_outcome"]);
    echo "Adding Fight details...<br>";

    if (!empty($_POST["insert_ffID"]) && !empty($_POST["insert_sfID"]) && !empty($_POST["insert_date"]) && !empty($_POST["insert_city"]) && !empty($_POST["insert_country"]) && !empty($_POST["insert_outcome"] && $outcome < 3 && $outcome > -2)){
    $db = get_mysqli_connection();
    $query = $db->prepare("call addBout(?,?,?,?,?,?)");
    $query->bind_param("iisssi",$fighter1, $fighter2, $dateToInsert, $arenacity, $arenacountry, $outcome);
    if ($query->execute()) {    
        echo "Event added <br>";
    }
    else {
        echo "Error inserting: " . mysqli_error($db);
    }
    }
    else {
        echo "Event not added. <br>";
        echo "Make sure all fields have a value. <br>";
        echo "Make sure all fields have correct values. <br>";
    }
}
?>

<h2>DELETE A FIGHTER</h2>
<?php
$delete_form = new PhpFormBuilder();
$delete_form->set_att("method", "POST");
$delete_form->add_input("FighterID to delete", array(
    "type" => "number"
), "delete_id");
$delete_form->add_input("Delete", array(
    "type" => "submit",
    "value" => "Delete"
), "delete");
$delete_form->build_form();

if (isset($_POST["delete"])) {

    $idToDelete = htmlspecialchars($_POST["delete_id"]);
    echo "deleting...<br>";


    $db = get_mysqli_connection();
    $query = false;

    if (!empty($_POST["delete_id"])) {
        echo "deleting by id...";
        $query = $db->prepare("delete from Fighter where FighterID = ?");
        $query->bind_param("i", $idToDelete,);
    }
    if ($query) {
        $query->execute();
        $_SESSION["affected_rows"] = $db->affected_rows;
        
    }
    else{
        echo "Error executing delete query: " . mysqli_error($db);
    }
}
?>


<h2>VIEW A FIGHTER'S BOUT HISTORY</h2>
<?php
$select_form = new PhpFormBuilder();
$select_form->set_att("method", "POST");
$select_form->add_input("FighterID to search for", array(
    "type" => "number"
), "search_id");
$select_form->add_input("First and Last Name to search for", array(
    "type" => "text",
    "placeholder" => "Israel Adesanya"
), "search_bouts");
$select_form->add_input("Submit", array(
    "type" => "submit",
    "value" => "Search"
), "Bouts");
$select_form->build_form();

if (isset($_POST["Bouts"])) {
    echo "searching...<br>";

    $db = get_mysqli_connection();
    $query = false;

    if (!empty($_POST["search_id"]) && !empty($_POST["search_bouts"])) {
        echo "searching by FighterID not FName and LName...<br><br>";
        $query = $db->prepare("select * from FoughtIn where FirstFighterID = ? or SecondFighterID = ?");
        $query->bind_param("ii", $_POST["search_id"], $_POST["search_id"]);
    }
    else if (!empty($_POST["search_id"]) && empty($_POST["search_bouts"])) {
      echo "searching by FighterID ...<br><br>";
      $query = $db->prepare("select * from FoughtIn where FirstFighterID = ? or SecondFighterID = ?");
      $query->bind_param("ii", $_POST["search_id"],$_POST["search_id"]);
    }
    else if (!empty($_POST["search_bouts"])) {
        echo "searching by FName and LName...<br><br>";
        $query = $db->prepare("call findfighter(?)");
        $query->bind_param("s", $_POST["search_bouts"]);
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
</div>