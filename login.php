<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/style20.css" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
  </head>
  <body>
  <?php
    //database connect throuhg pdo
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

    //to check if present cookie is valid
    
    if(isset($_COOKIE['phpsession'])) {
      $checkloggedquery = $conn->prepare("SELECT email FROM userinfo WHERE phpsession=?");
      $checkloggedquery->execute([$_COOKIE['phpsession']]);
      $result= $checkloggedquery->fetchAll();

      if(count($result)==1) {
        $conn=null;
        echo("<h2>You are already logged in!!</h2>");
        echo("Redirecting to dasboard..");
        header("refresh:2;url=dashboard.php");
        die();  
      }
      $conn = null; 
    } 
  ?>
    <div style="background-image: url('assets/register.jpg')">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
          <a class="navbar-brand" href="index.php"><img src="assets/imageedit_1_8840684089.png" alt="" width="80" height="50"></a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="index.php">Home</a>
              </li>
              
              <li class="nav-item">
                <a class="nav-link" href="contact-us.php">Contact Us</a>
              </li>
            </ul>
           <div class="mx-2">
                <a href="signup.php"><button class="btn btn-light">SignUp</button></a>
           </div>

          </div>
        </div>
      </nav>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth auth-bg-1 theme-one">
          <div class="row w-100">
            <div class="col-lg-4 mx-auto">
              <div class="auto-form-wrapper">
                <form method="post" action="./newlogin.php" id="loginform" onsubmit="return validateForm()">
                  <div class="form-group">
                    <label class="label"><strong>Email</strong></label>
                    <div class="input-group">
                      <input type="text" class="form-control" placeholder="Email" name="email" required>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="label"><strong>Password</strong></label>
                    <div class="input-group">
                      <input type="password" class="form-control" placeholder="*********" name="password" required>
                      <div class="input-group-append">
                        <span class="input-group-text">
                          <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <button class="btn btn-primary submit-btn btn-block"><strong>Login</strong></button>
                  </div>
                  <div class="form-group d-flex justify-content-between">
                    <a href="forgotpass.php" class="text-small forgot-password text-black">Forgot Password</a>
                  </div>
                  <div class="text-block text-center my-3">
                    <span class="text-small font-weight-semibold">Not a member ?</span>
                    <a href="signup.php" class="text-black text-small">Create new account</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- page-body-wrapper ends -->
    </div>
    <script>
      function validateForm() {
        var email = document.getElementById("loginform")["email"].value;
        var password = document.getElementById("loginform")["password"].value;
        if (!/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email)) {
          alert("Email Not Valid.")
          return false;
        }
        if(password.length>=32){
          alert("Password length too big!!")
          return false;
        }
        return true;        
      } 
    </script>
  </body>
</html>
