<?php
function unexpectederror(){
  $conn=null;
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
        $username = $_POST['username'];
        $password = $_POST['password'];
        $confirmpass = $_POST['confirmpass'];
        if((!empty($email) and !empty($password)) and $password==$confirmpass) {
            if (filter_var($email, FILTER_VALIDATE_EMAIL) && strlen($password)<32) {
              //check if email already exists
              $checkemailquery = $conn->prepare("SELECT email FROM userinfo WHERE email = ?");
              $checkemailquery->execute(array($email));
              $row= $checkemailquery->fetchAll();
              if(count($row)==0)
              {
                //check if username already in use
                $checkusernamequery = $conn->prepare("SELECT email FROM userinfo WHERE username = ?");
                $checkusernamequery->execute(array($username));
                $row= $checkusernamequery->fetchAll();
                if(count($row)==0)
                {
                  $passwordhash=md5($password);
                  function generateRandomString() {
                    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    $charactersLength = strlen($characters);
                    $randomString = '';
                    $length=128;
                    for ($i = 0; $i < $length; $i++) {
                        $randomString .= $characters[rand(0, $charactersLength - 1)];
                    }
                    return $randomString;
                  }
                  $primarykey = generateRandomString();
                  
                  $createaccountquery = $conn->prepare("INSERT INTO userinfo (email, username, passwordhash, primarykey) VALUES (?,?,?,?)");
                  $createaccountquery->execute(array($email,$username,$passwordhash,$primarykey));
                  echo("<h2>Account created. Redirecting to login Page.</h2>");
                  $conn=null;
                  header("refresh:2;url=login.php");
                  die();
                }
                else{
                  $conn=null;
                  echo("<h2>Username already taken. Try another one.</h2><br>Redirecting...");             
                  header("refresh:2;url=signup.php");
                  die();     
                }
              }
              else{
                $conn=null;
                echo("<h2>Email already registered. Try logging in.</h2><br>Redirecting...");
                header("refresh:2;url=login.php");
                die();     
              }
            }
          }
          else {unexpectederror();}
} else {
    echo("<h2>Sorry, Method not allowed!!</h2>");
}
?>