<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Index</title>
    <!--
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    reference: https://www.w3schools.com/css/css_rwd_viewport.asp 
-->
    <link rel=stylesheet type="text/css" href="PHPDB.css">
    <style>
        caption {
            padding: 2%;
            font-weight: bold;
            font-size: 120%;
        }

    </style>
</head>

<body>
    <h1>Classic Models company</h1>
    <?php include 'site_navigation.php';  ?>
    <h2 class="title"> Customers Info </h2>
    <?php
    function customError($errno, $errstr) {
        echo "<b>Error:</b> Type: $errstr <br> Number: $errno<br>";
        die();
    }
    set_error_handler("customError");   
 //    error handling reference: comp30640 lecture 17 ppt p.7 https://brightspace.ucd.ie/d2l/le/content/57726/viewContent/724323/View
    
$conn = new mysqli("localhost", "root", "", "classicmodels");   
    
if($conn === false){
    die("ERROR: Could not connect." . $conn->connect_error);
} //reference: https://www.tutorialrepublic.com/php-tutorial/php-mysql-select-query.php    
 

$sql = "SELECT customerName, phone, city, country FROM customers ORDER BY country";
   
//error handling reference: https://www.tutorialrepublic.com/php-tutorial/php-mysql-select-query.php

if($result = $conn->query($sql)){
    if($result->num_rows > 0){
        echo "<table><tr>";
        echo "<th>Customer Name</th>";
        echo "<th>Phone Number</th>";
        echo "<th>City</th>";
        echo "<th>Country</th></tr>";
        while($row = $result->fetch_array()){
            echo "<tr><td>".$row["customerName"] . "</td>";
            echo "<td>".$row["phone"] . "</td>";
            echo "<td>".$row["city"] . "</td>";
            echo "<td>".$row["country"] . "</td></tr>";  
            }
            echo "</table>";

        $result->free();
    } else{
        echo "Error: No results were found.";
    }
} else{
    echo "ERROR: Could not execute $sql." . $conn->error;
}
 

    
$conn->close();    
 
?>

    <footer> <?php include('footer.php'); ?> </footer>

</body>

</html>
