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
    <h2 class="title"> Orders Info </h2>
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
 

$sql = "SELECT orderNumber, orderDate, status FROM orders WHERE status = 'In Process'";
   
//error handling reference: https://www.tutorialrepublic.com/php-tutorial/php-mysql-select-query.php
if($result = $conn->query($sql)){
    if($result->num_rows > 0){     
        echo "<table><tr>";
        echo "<caption>Orders that currently in process</caption>";
        echo "<th>Order Number</th>";
        echo "<th>Order Date</th>";
        echo "<th>Status</th></tr>";
        while($row = $result->fetch_array()){
            echo "<tr><td>"."<form action='orders.php' method='post'> 
                <button type='submit' class='productinfo' name='orderdetails' value='$row[orderNumber]'> $row[orderNumber] </button></form>". "</td>";            
            echo "<td>".$row["orderDate"] . "</td>";
            echo "<td>".$row["status"] . "</td></tr>";
            }
            echo "</table>";
        $result->free();
    } else{
        echo "Error:No results were found.";
    }
} else{
    echo "ERROR: Could not execute $sql." . $conn->error;
}
 
    
$sql = "SELECT orderNumber, orderDate, status FROM orders WHERE status = 'Cancelled'";
   
//error handling reference: https://www.tutorialrepublic.com/php-tutorial/php-mysql-select-query.php
if($result = $conn->query($sql)){
    if($result->num_rows > 0){ 
        echo "<table><tr>";
        echo "<caption>Cancelled Orders</caption>";
        echo "<th>Order Number</th>";
        echo "<th>Order Date</th>";
        echo "<th>Status</th></tr>";
        while($row = $result->fetch_array()){
            echo "<tr><td>"."<form action='orders.php' method='post'> 
                <button type='submit' class='productinfo' name='orderdetails' value='$row[orderNumber]'> $row[orderNumber] </button></form>". "</td>";            
            echo "<td>".$row["orderDate"] . "</td>";
            echo "<td>".$row["status"] . "</td></tr>";         
            }
            echo "</table>";

        $result->free();
    } else{
        echo "Error:No results were found.";
    }
} else{
    echo "ERROR: Could not execute $sql." . $conn->error;
}   
    
//limit reference: https://www.w3schools.com/php/php_mysql_select_limit.asp
$sql = "SELECT orderNumber, orderDate, status FROM orders ORDER BY orderDate DESC LIMIT 20";
   
//error handling reference: https://www.tutorialrepublic.com/php-tutorial/php-mysql-select-query.php
if($result = $conn->query($sql)){
    if($result->num_rows > 0){
        echo "<table><tr>";
        echo "<caption>The 20 Most Recent Orders</caption>";
        echo "<th>Order Number</th>";
        echo "<th>Order Date</th>";
        echo "<th>Status</th></tr>";
        while($row = $result->fetch_array()){
            echo "<tr><td>"."<form action='orders.php' method='post'> 
                <button type='submit' class='productinfo' name='orderdetails' value='$row[orderNumber]'> $row[orderNumber] </button></form>". "</td>";            
            echo "<td>".$row["orderDate"] . "</td>";
            echo "<td>".$row["status"] . "</td></tr>";
            }
            echo "</table>";
        $result->free();
    } else{
        echo "Error:No results were found.";
    }
} else{
    echo "ERROR: Could not execute $sql." . $conn->error;
}   
        
    
    
// https://www.sitepoint.com/community/t/if--server-request-method-post-vs-isset-submit/252336/4   
if ($_SERVER['REQUEST_METHOD'] === 'POST' and ($_POST['orderdetails'])){
    $orderNumber = $_POST['orderdetails'];    
    
    $sql = "SELECT p.productCode, p.productLine, p.productName, o.comments
        FROM orders o, orderdetails od, products p
        WHERE o.orderNumber = od.orderNumber AND 
        p.productCode = od.productCode AND
        od.orderNumber = $orderNumber";
    
    
    if($result = $conn->query($sql)){
        if($result->num_rows > 0){
        echo "<div id='wrapper1' class='modal'>";
        echo '<div id="content1" class="product_details">';
        echo "  <script>var contents = ' <span class=\"close\">&times;</span>';
                document.getElementById('content1').innerHTML += contents;</script>";  
            echo "<table><tr>";
            echo "<caption>Order $orderNumber 's information</caption>";
            echo "<th>Product Code</th>";
            echo "<th>Product Line</th>";
            echo "<th>Product Name</th>";
            echo "<th>Order Comments</th></tr>";
            while($row = $result->fetch_array()){
                echo "<tr><td>".$row["productCode"] . "</td>";
                echo "<td>".$row["productLine"] . "</td>";
                echo "<td>".$row["productName"] . "</td>";
                if ($row["comments"]==""){
                    echo "<td>"."No comments"."</td></tr>";
                }else{
                    echo "<td>".$row["comments"] . "</td></tr>";
                    }

            }
            echo "</table>";
            echo '</div>';
            echo '</div>';
            $result->free();
        } else{
            echo "Error:No results were found.";
        }
    } else{
        echo "ERROR: Could not execute $sql." . $conn->error;
    }     
        
     
}
    
$conn->close();    
 
?>

    <script>
        var detailsDiv1 = document.getElementById("wrapper1");

        detailsDiv1.style.display = "block";
        //modal reference:https://www.w3schools.com/howto/howto_css_modals.asp

        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            detailsDiv1.style.display = "none";
        };
        window.onclick = function(event) {
            if (event.target == detailsDiv1) {
                detailsDiv1.style.display = "none";
            }
        }

    </script>

    <footer> <?php include('footer.php'); ?> </footer>

</body>

</html>
