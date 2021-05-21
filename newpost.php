<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Post</title>
</head>
<body>
<?php
$title = $_POST['title'];
$content = $_POST['content'];
//get user cookie
if ($_SERVER["REQUEST_METHOD"] == "POST")   {
    if(!empty($title) and !empty($content)) {
        $usersession=$_COOKIE['phpsession'];
        if(!isset($usersession)) {
        echo("<h2>You are not logged in. Please log in first to continue!</h2>");
        echo("Redirecting...");
        header("refresh:2;url=login.php");
        die();
        } else {
        //connect to database and extract userinfo
        $servername = "localhost";
        $username = "aminet";
        $password = "password";
        $dbname= "aminetweb";

        try {
            $conn = new PDO("mysql:host=$servername;dbname=aminetweb", $username, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            echo "Database Connection failed: " . $e->getMessage();
        }


        //search for cookie in the database
        $searchcookiequery = $conn->prepare("SELECT primarykey FROM userinfo WHERE phpsession=?");
        $searchcookiequery->execute([$usersession]);
        $result= $searchcookiequery->fetchAll();

        if(count($result)==1) {
            //insert new post content in database
            $primarykey=$result[0][primarykey];
            $insertpostquery= $conn->prepare("INSERT INTO posts (primarykey, title, content, postedtime) VALUES (?, ?, ?, current_time())");
            $insertpostquery->execute(array($primarykey,$title,$content));
            $conn=null;
            echo("<h2>Posted!!</h2>");
            echo("Redirecting...");
            header("refresh:2;url=dashboard.php");
            die();
        }
        else {
            $conn=null;
            echo("<h2>You are not logged in!!</h2>");
            echo("Redirecting...");
            header("refresh:2;url=index.php");
            die();
        }

        $conn = null; 
        }
    }
}
?>
</body>
</html>