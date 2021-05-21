<?php
function unexpectederror(){
  echo "<h1>Sorry, an unexpected error occured</h1>";    
}

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
//  **** get data like this ****
// $stmt = $conn->prepare("SELECT * FROM userinfo;");
// $stmt->execute();
// while($row = $stmt->fetch()) {
//     print_r($row);
//     echo '<br>email is :'.$row[email];
// }
//
if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST['email'];
        $password = $_POST['password'];
        if(!empty($email) and !empty($password)) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password)<32) {
              $stmt = $conn->prepare("SELECT email,passwordhash FROM userinfo WHERE email = ?");
              $stmt->execute(array($email));
              $row= $stmt->fetchAll();
              //$row[0][email]=root@aminet.web
              //$row[0][passwordhash]=hash
              if(count($row)==1){
                //check password hash, if true set cookie
                if(md5($password)==$row[0][passwordhash])
                {
                  echo("<h2>Welcome..</h2>");
                  echo("Redirecting to your Dashboard.");
                  //create random cookie value
                  $cookie_value= session_create_id();
                  //set cookie when login successfull
                  setcookie("phpsession", $cookie_value, time() + (86400 * 30), "/");
                  //update cookie in the database
                  $updatecookiequery = $conn->prepare("UPDATE userinfo SET phpsession=? WHERE email=?");
                  $updatecookiequery->execute(array($cookie_value,$email));
                  $conn=null;
                  header("refresh:2;url=dashboard.php");
                  die();                
                }
                else
                {
                  echo("<h2>These credentials doesn't seem right, please try again!!</h2>");
                  $conn=null;
                  header("refresh:2;url=login.php");
                  die();
                }
              }
              else {unexpectederror();}
            }
          }
          else {unexpectederror();}
} else {
    echo("<h2>Sorry, Method not allowed!!</h2>");
}
$conn=null;
?>