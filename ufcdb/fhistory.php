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

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?= $PROJECT_NAME . " | RECORDS "?></title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles/style.css">
    <link rel="stylesheet" href="styles/buttons.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
    body {
        margin:40px 40px 40px 40px;
        max-width:100%;
        background-repeat: no-repeat;
        background-image: url('img/ufc.jpg');
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
    float: right;
   }
   .table-striped>tbody>tr:nth-child(odd)>td, 
   .table-striped>tbody>tr:nth-child(odd)>th {
   background-color: rgba(170, 14, 14, 0.75); 
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
  button, input, optgroup, select, textarea {
    color: black;
    font: inherit;
    margin: 0;
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
<div class="title"><h1><?= $PROJECT_NAME . " | FIGHT RECORDS "?></h1></div>
<br>
<a class="logout" href="logout.php"><button class="sub">LOG OUT</button></a>
<br>
<div style="margin-top: 30px; display: grid; grid-template-columns: 1fr 1fr 1fr;">
  <div class="qlink" style="background-color: rgb(216, 188, 29);"><a href="champs.php">Champions</a></div>
  <div class="qlink" style="background-color: rgb(153, 37, 37);"><a href="wc.php">Weight Class</a></div>
  <div class="qlink" style="background-color: rgb(0, 0, 0);"><a href="index.php">Home</a></div>
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
        $query = $db->prepare("select * from FoughtIn where FirstFighterID = ? or SecondFighterID = ?");
        $query->bind_param("ii", $_POST["search_id"],$_POST["search_id"]);
    }
    else if (!empty($_POST["search_id"]) && empty($_POST["search_data"])) {
      echo "searching by FighterID ...<br><br>";
      $query = $db->prepare("select * from FoughtIn where FirstFighterID = ? or SecondFighterID = ?");
      $query->bind_param("ii", $_POST["search_id"],$_POST["search_id"]);
    }
    else if (!empty($_POST["search_data"])) {
        echo "searching by FName and LName...<br><br>";
        $query = $db->prepare("call findfighter(?)");
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
<h2>RECENT FIGHTS</h2>

<?php

$db = get_mysqli_connection();
$query = false;
$query = $db->prepare("call getbouts()");

if ($query) {
    $query->execute();
    $result = $query->get_result();
    $rows = $result->fetch_all(MYSQLI_ASSOC);
    echo makeTable($rows);
    $query->close();   
}
else{
    echo "Error executing query: " . mysqli_error($db);
}
?>

<br>
<br>
<h2>FIGHTERS</h2>


<table class="table table-striped table-bordered">
<thead>
<tr>
<th style='width:50px;'>FighterID</th>
<th style='width:150px;'>First Name</th>
<th style='width:50px;'>Last Name</th>
<th style='width:150px;'>Nickname</th>
<th style='width:50px;'>DOB</th>
<th style='width:150px;'>Stance</th>
<th style='width:50px;'>Weight</th>
<th style='width:150px;'>Height</th>
<th style='width:50px;'>Reach</th>
<th style='width:150px;'>Wins</th>
<th style='width:50px;'>Losses</th>
<th style='width:150px;'>Draws</th>
</tr>
</thead>
<tbody>

<?php
$db = get_mysqli_connection();
if (isset($_GET['page_no']) && $_GET['page_no']!="") {
    $page_no = $_GET['page_no'];
    } 
else {
    $page_no = 1;
    }
$total_records_per_page = 50;
$offset = ($page_no-1) * $total_records_per_page;
$previous_page = $page_no - 1;
$next_page = $page_no + 1;
$adjacents = "2";
$result_count = mysqli_query($db,"SELECT COUNT(*) As total_records FROM Fighter");
$total_records = mysqli_fetch_array($result_count, MYSQLI_ASSOC);
$total_records = $total_records['total_records'];
$total_no_of_pages = ceil($total_records / $total_records_per_page);
$second_last = $total_no_of_pages - 1; // total pages minus 1
$result = mysqli_query($db,"SELECT * FROM Fighter LIMIT $offset, $total_records_per_page");
while($row = mysqli_fetch_array($result)){
    echo "<tr>
	 <td>".$row['FighterID']."</td>
	 <td>".$row['FName']."</td>
	 <td>".$row['LName']."</td>
	 <td>".$row['NName']."</td>
     <td>".$row['DOB']."</td>
	 <td>".$row['Stance']."</td>
	 <td>".$row['Weight']."</td>
	 <td>".$row['Height']."</td>
     <td>".$row['Reach']."</td>
	 <td>".$row['Wins']."</td>
	 <td>".$row['Loss']."</td>
	 <td>".$row['Draw']."</td>
	 </tr>";
        }
mysqli_close($db);
?>
</tbody>
</table>
<ul class="pagination">
<?php if($page_no > 1){
echo "<li><a href='?page_no=1'>First Page</a></li>";
} ?>
    
<li <?php if($page_no <= 1){ echo "class='disabled'"; } ?>>
<a <?php if($page_no > 1){
echo "href='?page_no=$previous_page'";
} ?>>Previous</a>
</li>

<li><?php
if ($total_no_of_pages <= 10){  	 
	for ($counter = 1; $counter <= $total_no_of_pages; $counter++){
	if ($counter == $page_no) {
	echo "<li class='active'><a>$counter</a></li>";	
	        }else{
        echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                }
        }
}
elseif ($total_no_of_pages > 10){
    if($page_no <= 4) {			
        for ($counter = 1; $counter < 8; $counter++){		 
           if ($counter == $page_no) {
              echo "<li class='active'><a>$counter</a></li>";	
               }else{
                  echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                       }
       }
       echo "<li><a>...</a></li>";
       echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
       echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
       }
       elseif($page_no > 4 && $page_no < $total_no_of_pages - 4) {		 
        echo "<li><a href='?page_no=1'>1</a></li>";
        echo "<li><a href='?page_no=2'>2</a></li>";
        echo "<li><a>...</a></li>";
        for (
             $counter = $page_no - $adjacents;
             $counter <= $page_no + $adjacents;
             $counter++
             ) {		
             if ($counter == $page_no) {
            echo "<li class='active'><a>$counter</a></li>";	
            }else{
                echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                  }                  
               }
        echo "<li><a>...</a></li>";
        echo "<li><a href='?page_no=$second_last'>$second_last</a></li>";
        echo "<li><a href='?page_no=$total_no_of_pages'>$total_no_of_pages</a></li>";
        }
        else {
            echo "<li><a href='?page_no=1'>1</a></li>";
            echo "<li><a href='?page_no=2'>2</a></li>";
            echo "<li><a>...</a></li>";
            for (
                 $counter = $total_no_of_pages - 6;
                 $counter <= $total_no_of_pages;
                 $counter++
                 ) {
                 if ($counter == $page_no) {
                echo "<li class='active'><a>$counter</a></li>";	
                }else{
                    echo "<li><a href='?page_no=$counter'>$counter</a></li>";
                }                   
                 }
            }
    }
?>
</li>


    
<li <?php if($page_no >= $total_no_of_pages){
echo "class='disabled'";
} ?>>
<a <?php if($page_no < $total_no_of_pages) {
echo "href='?page_no=$next_page'";
} ?>>Next</a>
</li>

<?php if($page_no < $total_no_of_pages){
echo "<li><a href='?page_no=$total_no_of_pages'>Last &rsaquo;&rsaquo;</a></li>";
} ?>
</ul>
<div style='padding: 10px 20px 0px; border-top: dotted 1px #CCC;'>
<strong>Page <?php echo $page_no." of ".$total_no_of_pages; ?></strong>
</div>

</div>


