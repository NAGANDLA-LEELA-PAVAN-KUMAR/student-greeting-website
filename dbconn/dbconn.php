<?php
$servername = "localhost";
$username = "root";
$password = "";

try 
	{
	//here dob is database name
    $conn = new PDO("mysql:host=$servername;dbname=dob", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // echo "Connected successfully"; 
    }
catch(PDOException $e)
    {
    //echo "Connection failed: " . $e->getMessage();
    }
?>
<!--BOOSTRAP FILES-->
<link rel="stylesheet" href="bootstrap-4.1.3-dist/css/bootstrap.min.css">
<script src="bootstrap-4.1.3-dist/js/bootstrap.min.js"></script>
