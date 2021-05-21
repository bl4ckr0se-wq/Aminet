<?php
//delete cookie from database
$servername = "localhost";
$username = "aminet";
$password = "password";
$dbname= "aminetweb";
    
    
    try {
      $conn = new PDO("mysql:host=$servername;dbname=aminetweb", $username, $password);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
      echo "Database Connection failed: " . $e->getMessage();
      die();
    }

    //to check if present cookie exists in db and deleting it
    
    if(isset($_COOKIE['phpsession'])) {
      $checkloggedquery = $conn->prepare("UPDATE userinfo SET phpsession='' WHERE phpsession=?");
      $checkloggedquery->execute([$_COOKIE['phpsession']]);
      $conn = null; 
    } 


setcookie("phpsession", "", time() - 3600);
echo("<h2>You are being logged out. Good-Bye</h2>");
header("refresh:2;url=index.php");
die();
?>