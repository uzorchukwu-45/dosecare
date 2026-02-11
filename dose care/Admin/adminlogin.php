
<?php
session_start();
include '../config.php'; // Using the $pdo variable from config.php

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // FR3: Authenticate users with username and password
    // Security (4.2): Prepared statements prevent SQL Injection
    try {
        $stmt = $pdo->prepare("SELECT id, username, password FROM adminuser WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user) {
            // Verify the hashed password from the database
            if (password_verify($password, $user['password'])) {
                // Login success - Set Session variables
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_username']  = $user['username'];
                $_SESSION['admin_id']        = $user['id'];

                // Redirect to the Dashboard (FR21-23)
                header("Location: index.php");
                exit();
            } else {
                echo "<script>alert('Invalid Password'); window.location='adminlogin.php';</script>";
            }
        } else {
            echo "<script>alert('Admin user not found'); window.location='adminlogin.php';</script>";
        }
    } catch (Exception $e) {
        // Log the error in a real production environment
        die("System Error: " . $e->getMessage());
    }
}
?>










<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
     <link rel="stylesheet" href="admin.css">
  </head>
  <marquee behavior="" direction="">

  <h1  id="welcome-header">Welcome to Dose Care Admin Panel</h1>
  </marquee>
  

  <div id="login-container">
    <div class="login-header">
        <h2>Parklane Hospital</h2>
        <p>Admin Adherence Portal</p>
    </div>

    <form action="adminlogin.php" method="POST">
        <div class="mb-3 row">
            <label for="username" class="col-sm-2 col-form-label">Username</label>
            <div class="col-sm-10">
                <input type="text" name="username" id="username" placeholder="Enter Admin Username" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
                <input type="password" name="password" class="form-control" id="inputPassword" placeholder="••••••••" required>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-sm-10 offset-sm-2">
                <button type="submit" name="login" id="login-btn">
                    LOGIN TO SYSTEM
                </button>
            </div>
        </div>
    </form>
</div>









    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>



















