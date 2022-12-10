<?php
require_once("config.php");
?>

<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1><?= $PROJECT_NAME?></h1>

<?php
if (!empty($_SESSION["affected_rows"])) {
    echo "Deleted " . $_SESSION["affected_rows"] . " rows";
    unset($_SESSION["affected_rows"]);
}
?>

<h2>SQL SELECT -> HTML Table using <a href="https://www.php.net/manual/en/book.pdo.php">PDO</a></h2>
<?php

$db = get_pdo_connection();
$query = $db->prepare("SELECT * FROM hello");
$query->execute();
$rows = $query->fetchAll(PDO::FETCH_ASSOC);

echo makeTable($rows);
?>


<h2>SQL SELECT using input from form</h2>
<?php
$select_form = new PhpFormBuilder();
$select_form->set_att("method", "POST");
$select_form->add_input("id to search for", array(
    "type" => "number"
), "search_id");
$select_form->add_input("data to search for", array(
    "type" => "text"
), "search_data");
$select_form->add_input("Submit", array(
    "type" => "submit",
    "value" => "Search"
), "search");
$select_form->build_form();

if (isset($_POST["search"])) {
    echo "searching...<br>";

    $db = get_pdo_connection();
    $query = false;

    if (!empty($_POST["search_id"])) {
        echo "searching by id...";
        $query = $db->prepare("select * from hello where id = :id");
        $query->bindParam(":id", $_POST["search_id"], PDO::PARAM_INT);
    }
    else if (!empty($_POST["search_data"])) {
        echo "searching by data...";
        $query = $db->prepare("select * from hello where data = :data");
        $query->bindParam(":data", $_POST["search_data"], PDO::PARAM_STR);
    }
    if ($query) {
        $query->execute();
        $rows = $query->fetchAll(PDO::FETCH_ASSOC);
        echo makeTable($rows);
    }
    else{
        echo "Error executing query: " . $db->errorInfo();
    }
}

?>

<h2>SQL INSERT using input from form</h2>

<?php
$insert_form = new PhpFormBuilder();
$insert_form->set_att("method", "POST");
$insert_form->add_input("data to insert", array(
    "type" => "text"
), "insert_data");
$insert_form->add_input("Insert", array(
    "type" => "submit",
    "value" => "Insert"
), "insert");
$insert_form->build_form();

if (isset($_POST["insert"]) && !empty($_POST["insert_data"])) {
    $dataToInsert = htmlspecialchars($_POST["insert_data"]);
    echo "inserting $dataToInsert ...";

    $db = get_pdo_connection();
    $query = $db->prepare("insert into hello (data) values (?)");
    $query->bindParam(1, $dataToInsert, PDO::PARAM_STR);
    if ($query->execute()) {    
        header( "Location: " . $_SERVER['PHP_SELF']);
    }
    else {
        echo "Error inserting: " . $db->errorInfo();
    }
}
?>

<h2>SQL UPDATE using input from form</h2>

<?php
$update_form = new PhpFormBuilder();
$update_form->set_att("method", "POST");
$update_form->add_input("id to update data for", array(
    "type" => "number"
), "update_id");
$update_form->add_input("data to update", array(
    "type" => "text"
), "update_data");
$update_form->add_input("Update", array(
    "type" => "submit",
    "value" => "Update"
), "update");
$update_form->build_form();

if (isset($_POST["update"]) 
    && !empty($_POST["update_data"])
    && !empty($_POST["update_id"])) {
    $dataToUpdate = htmlspecialchars($_POST["update_data"]);
    $idToUpdate = htmlspecialchars($_POST["update_id"]);
    echo "updating $dataToUpdate ...";

    $db = get_pdo_connection();
    $query = $db->prepare("update hello set data= ? where id = ?");
    $query->bindParam(1, $dataToUpdate, PDO::PARAM_STR);
    $query->bindParam(2, $idToUpdate, PDO::PARAM_INT);
    if ($query->execute()) {    
        header( "Location: " . $_SERVER['PHP_SELF']);
    }
    else {
        echo "Error updating: " . $db->errorInfo();
    }
}

?>

<h2>SQL DELETE using input from form</h2>

<?php
$delete_form = new PhpFormBuilder();
$delete_form->set_att("method", "POST");
$delete_form->add_input("id to delete for", array(
    "type" => "number"
), "delete_id");
$delete_form->add_input("data to delete", array(
    "type" => "text"
), "delete_data");
$delete_form->add_input("Delete", array(
    "type" => "submit",
    "value" => "Delete"
), "delete");
$delete_form->build_form();

if (isset($_POST["delete"])) {

    echo "deleting...<br>";

    $db = get_pdo_connection();
    $query = false;

    if (!empty($_POST["delete_id"])) {
        echo "deleting by id...";
        $query = $db->prepare("delete from hello where id = ?");
        $query->bindParam(1, $_POST["delete_id"], PDO::PARAM_INT);
    }
    else if (!empty($_POST["delete_data"])) {
        echo "deleting by data...";
        $query = $db->prepare("delete from hello where data = ?");
        $query->bindParam(1, $_POST["delete_data"], PDO::PARAM_STR);
    }
    if ($query) {
        $query->execute();
        $_SESSION["affected_rows"] = $query->rowCount();
        header("Location: " . $_SERVER["PHP_SELF"]);
    }
    else{
        echo "Error executing delete query: " . $db->errorInfo();
    }
}
?>