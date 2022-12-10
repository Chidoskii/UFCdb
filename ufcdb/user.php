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
    echo "searching...<br>";

    $db = get_mysqli_connection();
    $query = false;

    if (!empty($_POST["search_id"]) && !empty($_POST["search_data"])) {
        echo "searching by FighterID not FName and LName...<br><br>";
        $query = $db->prepare("select * from Fighter where FighterID = ?");
        $query->bind_param("i", $_POST["search_id"]);
    }
    else if (!empty($_POST["search_id"]) && empty($_POST["search_data"])) {
      echo "searching by FighterID ...<br><br>";
      $query = $db->prepare("select * from Fighter where FighterID = ?");
      $query->bind_param("i", $_POST["search_id"]);
    }
    else if (!empty($_POST["search_data"])) {
        echo "searching by FName and LName...<br><br>";
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


</div>