<?php include 'config.php'; ?>
<?php    session_start();   



 ?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <title></title>
  </head>
  <body>
  


<nav class="navbar navbar-expand-lg navbar-blue bg-body-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
        <img src="./images/doselogo.png" alt="logo" width="100">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
            
        <li class="nav-item">
          <a class="nav-link active" href="index.php">Home</a>
        </li>

         <?php if (!isset($_SESSION['user_id'])): ?>
            <li class="nav-item">
              <a class="nav-link" href="register.php">Register</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
        <?php else: ?>
            <li class="nav-item">
              <a class="nav-link" href="logout.php">Logout</a>
            </li>
        <?php endif; ?>
      </ul>
    </div>
  </div>

  <marquee behavior="scroll" direction="left">
    <h1 class="headline">
      <?php 
        if (isset($_SESSION['user_id'])) {
            // Requirement: Display Welcome message on dashboard
            echo "Welcome to the Dashboard, " . htmlspecialchars($_SESSION['full_name']) . "!";
        } else {
            echo "Your health, on schedule: Master your recovery with DoseCare.";
        }
      ?>
    </h1>
  </marquee>
</nav>






    
  </body>