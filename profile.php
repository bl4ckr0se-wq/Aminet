<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/3.7.95/css/materialdesignicons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

    <style type="text/css">
    	body{margin-top:20px;}

      body {
          color: #6c7293;
      }

      .profile-navbar .nav-item .nav-link {
        color: #6c7293;
      }

      .profile-navbar .nav-item .nav-link.active {
        color: #464dee;
      }

      .profile-navbar .nav-item .nav-link i {
        font-size: 1.25rem;
      }

      .profile-feed-item {
        padding: 1.5rem 0;
        border-bottom: 1px solid #e9e9e9;
      }
      .img-sm {
          width: 43px;
          height: 43px;
      }
    </style>
</head>
<body>
<?php

  //get user cookie
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
    //  **** get data like this ****
    // $stmt = $conn->prepare("SELECT * FROM userinfo;");
    // $stmt->execute();
    // while($row = $stmt->fetch()) {
    //     print_r($row);
    //     echo '<br>email is :'.$row[email];
    // }

    //search for cookie in the database
    $searchcookiequery = $conn->prepare("SELECT email,username,primarykey FROM userinfo WHERE phpsession=?");
    $searchcookiequery->execute([$usersession]);
    $result= $searchcookiequery->fetchAll();

    if(count($result)==1) {
      //get all profile info
      $email=$result[0][email];
      $username=$result[0][username];
      $primarykey=$result[0][primarykey];
      $getprofileinfoquery = $conn->prepare("SELECT fullname,profileimagelocation,phone,skills,twitter,linkedin,description FROM userprofile WHERE primarykey=?");
      $getprofileinfoquery->execute([$primarykey]);
      $result= $getprofileinfoquery->fetchAll();
      $fullname=$result[0][fullname];
      $profileimagelocation=$result[0][profileimagelocation];
      $profileimagelocation = './profilepictures/'.$profileimagelocation;      //update profile image location
      $phone=$result[0][phone];
      $skills=$result[0][skills];
      $twitter=$result[0][twitter];
      $linkedin=$result[0][linkedin];
      $description=$result[0][description];

      //get all posts written by user
      $getpostsquery = $conn->prepare("SELECT title,content,postedtime FROM posts WHERE primarykey=?");
      $getpostsquery->execute([$primarykey]);
      $result= $getpostsquery->fetchAll();

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
?>
<div class="container">
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
                <a class="nav-link" href="contact-us.html">Contact Us</a>
              </li>
            </ul>
            <div class="mx-2">
                        <a href="dashboard.php"><button class="btn btn-light">Dashboard</button></a>
            </div>
            <div class="mx-2">
                        <a href="logout.php"><button class="btn btn-light">Logout</button></a>
            </div>
            <div style="height: 4ch;">
                <a href="#"><img class="rounded-circle account-img" style="height: 5ch; width: 5ch;" src="assets/profile.png"></a>
            </div>
          </div>
        </div>
      </nav>


      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-lg-4">
                  <div class="border-bottom text-center pb-4">
                    <img src="<?php echo($profileimagelocation); ?>" alt="profile" class="rounded mw-100">
                    <div class="mb-3">
                      <h3><?php echo($fullname); ?></h3>
                      <h3>Username: @<?php echo($username); ?></h3>
                      <div class="d-flex align-items-center justify-content-center">
                        <div class="br-wrapper br-theme-css-stars"><select id="profile-rating" name="rating" autocomplete="off" style="display: none;">
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                        </select><div class="br-widget"><a href="#" data-rating-value="1" data-rating-text="1" class="br-selected br-current"></a><a href="#" data-rating-value="2" data-rating-text="2"></a><a href="#" data-rating-value="3" data-rating-text="3"></a><a href="#" data-rating-value="4" data-rating-text="4"></a><a href="#" data-rating-value="5" data-rating-text="5"></a></div></div>
                      </div>
                    </div>
                    <p class="w-75 mx-auto mb-3"><?php echo($description); ?> </p>
                    <div class="d-flex justify-content-center">
                      <button class="btn btn-success" onclick="alert('To be added soon ;)')">Follow</button>
                    </div>
                  </div>
                  <div class="border-bottom py-4">
                    <p>Skills</p>
                    <div>
                      <label class="badge badge-outline-dark">display</label>
                      <label class="badge badge-outline-dark">Hand lettering</label>
                      <label class="badge badge-outline-dark">Information Design</label>
                      <label class="badge badge-outline-dark">Graphic Design</label>
                      <label class="badge badge-outline-dark">Web Design</label>  
                    </div>                                                               
                  </div>
                  <div class="py-4">
                    <p class="clearfix">
                      <span class="float-left">
                        Phone
                      </span>
                      <span class="float-right text-muted">
                      <?php echo($phone); ?>
                      </span>
                    </p>
                    <p class="clearfix">
                      <span class="float-left">
                        Mail
                      </span>
                      <span class="float-right text-muted">
                        <?php echo($email); ?>
                      </span>
                    </p>
                    <p class="clearfix">
                      <span class="float-left">
                        Twitter
                      </span>
                      <span class="float-right text-muted">
                        <a href="<?php echo($twitter); ?>">@Suthar3Trilok</a>
                      </span>
                    </p>
                    <p class="clearfix">
                      <span class="float-left">
                        LinkedIn
                      </span>
                      <span class="float-right text-muted">
                        <a href="<?php echo($linkedin); ?>">@Trilok-Suthar</a>
                      </span>
                    </p>
                  </div>
                </div>
                <div class="col-lg-8">
                  <div class="d-block d-md-flex justify-content-between mt-4 mt-md-0">
                    <div class="text-center mt-4 mt-md-0">
                      <button class="btn btn-outline-primary" onclick="alert('Functionality not available yet. Will be added soon ;D')">Message</button>
                      </div>
                      <div class="text-center mt-4 mt-md-0">
                        <button class="btn btn-outline-primary" onclick="alert('Functionality not available yet. Will be added soon ;D')">Change Profile</button>
                        </div>
                  </div>
                  <div class="profile-feed">
                    <br>
                    <h3>Posts by @<?php echo($username) ?></h3>
                <?php
                //start for looop for posts , print all posts recursively
                foreach ($result as $post) {
                  echo('
                    <div class="d-flex align-items-start profile-feed-item">
                      <img src="'.$profileimagelocation.'" alt="profile" class="img-sm rounded-circle">
                      <div class="ml-4">
                      <h6>
                        '.$fullname.'@'.$username.'  
                        <small class="ml-4 text-muted"><i class="mdi mdi-clock mr-1"></i> '.$post[postedtime].'</small>
                      </h6>
                      <h5 style="color: #000;">
                        '.$post[title].'
                      </h5>
                        <p>
                        '.$post[content].'
                      </p>
                      </div>
                    </div>');
                }
                ?>
                <!-- This is a sample post -->
                    <!-- <div class="d-flex align-items-start profile-feed-item">
                      <img src="<?php echo($profileimagelocation); ?>" alt="profile" class="img-sm rounded-circle">
                      <div class="ml-4">
                        <h6>
                          Dylan Silva
                          <small class="ml-4 text-muted"><i class="mdi mdi-clock mr-1"></i>10 hours</small>
                        </h6>
                        <p>
                          When I first got into the online advertising business, I was looking for the magical combination 
                          that would put my website into the top search engine rankings
                        </p>
                        <img src="<?php echo($profileimagelocation); ?>" alt="sample" class="rounded mw-100">                                                        
                        <p class="small text-muted mt-2 mb-0">
                          <span>
                            <i class="mdi mdi-star mr-1"></i>4
                          </span>
                          <span class="ml-2">
                            <i class="mdi mdi-comment mr-1"></i>11
                          </span>
                          <span class="ml-2">
                            <i class="mdi mdi-reply"></i>
                          </span>
                        </p>
                      </div>
                    </div> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
<script src="js/jquery-1.10.2.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script type="text/javascript">
	
</script>
</body>
</html>