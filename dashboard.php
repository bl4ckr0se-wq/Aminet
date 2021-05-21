<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css'>

    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <style>
      body {
        background-color: #eee
      } 

      .time {
          font-size: 9px !important
      }

      .socials i {
          margin-right: 14px;
          font-size: 17px;
          color: #d2c8c8;
          cursor: pointer
      }

      .feed-image img {
          width: 100%;
          height: auto
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
  $searchcookiequery = $conn->prepare("SELECT email FROM userinfo WHERE phpsession=?");
  $searchcookiequery->execute([$usersession]);
  $result= $searchcookiequery->fetchAll();

  if(count($result)==1) {
    //get all posts is the database ordered by time
    $getallpostsquery = $conn->prepare("SELECT primarykey,title,content,postedtime FROM posts ORDER BY postedtime DESC");
    $getallpostsquery->execute();
    $result= $getallpostsquery->fetchAll();
  }
  else {
    $conn=null;
    echo("<h2>You are not logged in!!</h2>");
    echo("Redirecting...");
    header("refresh:2;url=index.php");
    die();
  }
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
                        <a href="logout.php"><button class="btn btn-light">Logout</button></a>
            </div>
            <div style="height: 4ch;">
                <a href="profile.php"><img class="rounded-circle account-img" style="height: 5ch; width: 5ch;" src="assets/profile.png"></a>
            </div>
          </div>
        </div>
      </nav>


      <div class="container mt-4 mb-5">
        <div class="d-flex justify-content-center row">
            <div class="row">
              <div class="col-md-4">
                <div class="content-section">
                  <h3>Sidebar</h3>
                  <p class='text-muted'>Users to check out!!
                    <ul class="list-group">
                      <li class="list-group-item list-group-item-light">@root</li>
                      <li class="list-group-item list-group-item-light">@average</li>
                      <li class="list-group-item list-group-item-light">@notrealroot</li>
                    </ul>
                  </p>
                </div>
              </div>
              <div class="col-md-8">
                <div class="feed p-2">
                    <div class="d-flex flex-row justify-content-between align-items-center p-2 bg-white border">
                        <div class="feed-text px-2">
                            <h4 class="text-black-50 mt-2">What's on your mind</h4>
                            <form action="/newpost.php" method="post">
                              <label for="Title">Title:</label><br>
                              <input type="text" name="title"><br><br>
                              <label for="lname">Content:</label><br>
                              <textarea rows="5" cols="80" name="content"></textarea> <br><br>
                              <input type="submit" value="Post">
                            </form> 
                        </div>
                        <div class="feed-icon px-2"><i class="fa fa-long-arrow-up text-black-50"></i></div>
                    </div>
                  <?php 
                  foreach ($result as $post) {
                    //get data from userprofile table
                    $primarykey=$post[primarykey];
                    $getdetailsquery = $conn->prepare("SELECT fullname,profileimagelocation FROM userprofile WHERE primarykey=?");
                    $getdetailsquery->execute([$primarykey]);
                    $detailresult= $getdetailsquery->fetchAll();
                    $profileimagelocation=$detailresult[0][profileimagelocation];
                    $profileimagelocation='./profilepictures/'.$profileimagelocation;
                    //get data from userinfo table
                    $getmoredetailsquery = $conn->prepare("SELECT username FROM userinfo WHERE primarykey=?");
                    $getmoredetailsquery->execute([$primarykey]);
                    $usernameresult= $getmoredetailsquery->fetchAll();
                    echo('<div class="bg-white border mt-2">
                    <div>
                        <div class="d-flex flex-row justify-content-between align-items-center p-2 border-bottom">
                            <div class="d-flex flex-row align-items-center feed-text px-2"><img class="rounded-circle" src="'.$profileimagelocation.'" width="45">
                                <div class="d-flex flex-column flex-wrap ml-2"><span class="font-weight-bold">'.$detailresult[0][fullname].'@'.$usernameresult[0][username].'</span><span class="text-black-50 time">'.$post[postedtime].'</span></div>
                            </div>
                            <div class="feed-icon px-2"><i class="fa fa-ellipsis-v text-black-50"></i></div>
                        </div>
                    </div>
                    <div class="p-2 px-3"><span>
                      '.$post[title].'<br>
                      '.$post[content].'
                    </span></div>
                    <div class="d-flex justify-content-end socials p-2 py-3"><i class="fa fa-thumbs-up"></i><i class="fa fa-comments-o"></i><i class="fa fa-share"></i></div>
                    </div>
                    ');
                  }
                  ?>
                  <!-- sample post -->
                    <!-- <div class="bg-white border mt-2">
                        <div>
                            <div class="d-flex flex-row justify-content-between align-items-center p-2 border-bottom">
                                <div class="d-flex flex-row align-items-center feed-text px-2"><img class="rounded-circle" src="assets/profile.png" width="45">
                                    <div class="d-flex flex-column flex-wrap ml-2"><span class="font-weight-bold">Thomson ben</span><span class="text-black-50 time">40 minutes ago</span></div>
                                </div>
                                <div class="feed-icon px-2"><i class="fa fa-ellipsis-v text-black-50"></i></div>
                            </div>
                        </div>
                        <div class="p-2 px-3"><span>Lorem</span></div>
                        <div class="feed-image p-2 px-3"><img class="img-fluid img-responsive" src="images/test2.jpg"></div>
                        <div class="d-flex justify-content-end socials p-2 py-3"><i class="fa fa-thumbs-up"></i><i class="fa fa-comments-o"></i><i class="fa fa-share"></i></div>
                    </div> -->
                </div>
            </div>
        </div>

    </div>
  </div>
 <script src='https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js'></script>
 <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
</body>
</html>