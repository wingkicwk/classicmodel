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
    <h2 class="title"> Product Line Info </h2>

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
} 
//reference: https://www.tutorialrepublic.com/php-tutorial/php-mysql-select-query.php    
    
    
$sql = "SELECT productLine, textDescription FROM productlines";
    
//error handling reference: https://www.tutorialrepublic.com/php-tutorial/php-mysql-select-query.php
if($result = $conn->query($sql)){
    if($result->num_rows > 0){
        echo "<table><tr>";
        echo "<th>Product Line</th>";
        echo "<th>Text Description</th></tr>";
        while($row = $result->fetch_array()){
            echo "<tr><td>".$row["productLine"] . "<form action='index.php' method='post'> 
            <button type='submit'  name='products' class='productinfo' value='$row[productLine]'> More Info </button></form>"."</td>";
            echo "<td>".$row["textDescription"] . "</td></tr>";
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
if ($_SERVER['REQUEST_METHOD'] === 'POST' and ($_POST['products'])){
    $productLine = $_POST['products'];
    
    //reference: https://www.tutorialrepublic.com/php-tutorial/php-mysql-select-query.php    
    $sql = "SELECT productLine,productCode, productName, productScale, productVendor, productDescription, quantityInStock, buyPrice, MSRP FROM products WHERE productLine = '$productLine'";

    if($result = $conn->query($sql)){
        if($result->num_rows > 0){
            echo "<div id='wrapper' class='modal'>";
            echo '<div id="content" class="product_details">';
            echo "  <script>var contents = ' <span class=\"close\">&times;</span>';
                    document.getElementById('content').innerHTML += contents;</script>";
            echo "<table><tr>";
            echo "<caption>Product Line: $productLine 's details</caption>";
            echo "<th>Product Code</th>";
            echo "<th>Product Name</th>";
            echo "<th>Product Scale</th>";
            echo "<th>Product Vendor</th>";
            echo "<th>Product Description</th>";
            echo "<th>Product Quantity In Stock</th>";
            echo "<th>Product Buy Price</th></tr>";
            while($row = $result->fetch_array()){
                echo "<tr><td>".$row["productCode"] . "</td>";
                echo "<td>".$row["productName"] . "</td>";
                echo "<td>".$row["productScale"] . "</td>";
                echo "<td>".$row["productVendor"] . "</td>";
                echo "<td>".$row["productDescription"] . "</td>";
                echo "<td>".$row["quantityInStock"] . "</td>";
                echo "<td>".$row["buyPrice"] . "</td></tr>";
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
        var detailsDiv = document.getElementById("wrapper");
        detailsDiv.style.display = "block";
        //modal reference:https://www.w3schools.com/howto/howto_css_modals.asp

        var span = document.getElementsByClassName("close")[0];
        span.onclick = function() {
            detailsDiv.style.display = "none";
        };
        window.onclick = function(event) {
            if (event.target == detailsDiv) {
                detailsDiv.style.display = "none";
            }
        }

    </script>

    <footer> <?php include('footer.php'); ?> </footer>

</body>

</html>
