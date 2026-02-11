
<?php
session_start();
require_once '../config.php';
if (!isset($_SESSION['admin_logged_in'])) { header("Location: adminlogin.php"); exit(); }

// Handle User Deletion (FR21)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: manageusers.php");
}

// Handle Staff Registration (FR2)
if (isset($_POST['add_staff'])) {
    $staff_id = $_POST['staff_id'];
    $full_name = $_POST['full_name'];
    $phone_number = $_POST['phone_number'];
    $unique_id = $_POST['unique_id'];
    $role = $_POST['role'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Security Requirement 4.2

    $stmt = $pdo->prepare("INSERT INTO users (staff_id, full_name,phone, unique_id, password, role) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$staff_id, $full_name, $phone_number, $unique_id, $password, $role]);
    echo "<script>alert('Staff Registered Successfully');</script>";
    // ADD THIS LINE RIGHT AFTER SUCCESSFUL INSERT:
    logSystemActivity($pdo, 'Admin', $_SESSION['admin_id'] ?? 'SuperAdmin', 'Add Staff', "Registered new staff: $full_name");
    
    echo "<script>alert('Staff Registered'); window.location='manageusers.php';</script>";
}

$users = $pdo->query("SELECT * FROM users")->fetchAll();
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
  
    <body class="admin-wrapper">
    <div class="main-content">
        <h2>User Management (FR21)</h2>
        <div class="data-table">
            <h3>Register New Staff (FR2)</h3>
            <form method="POST" style="display: flex; gap: 10px; margin-bottom: 20px;">
                <input type="text" name="staff_id" placeholder="Staff ID" required>
                <input type="text" name="full_name" placeholder="Full Name" required>
                <input type="password" name="password" placeholder="Password" required>
                     <input type="tel" name="unique_id" placeholder="Unique ID" required>
                 <input type="tel" name="phone_number" placeholder="Phone Number" required>
                <select name="role">
                    <option value="Doctor">Doctor</option>
                    <option value="Pharmacist">Pharmacist</option>
                    <option value="Nurse">Nurse</option>
                </select>
                <button type="submit" name="add_staff" class="btn-msg">Add Staff</button>
            </form>

            <table>
                <thead>
                    <tr><th>Staff ID</th><th>Username</th><th>Role</th><th>phone_number</th><th>unique_id</th><th>delete</th></tr>
                </thead>
                <tbody>
                   <?php foreach ($users as $u): ?>
                     <tr>
                           <td><?php echo htmlspecialchars($u['staff_id']); ?></td>
                           <td><?php echo htmlspecialchars($u['full_name']); ?></td>
                          <td><?php echo htmlspecialchars($u['role']); ?></td>
                          <td><?php echo htmlspecialchars($u['phone']); ?></td>
                          <td><?php echo htmlspecialchars($u['unique_id']); ?></td>
                          <td><a href="?delete=<?php echo $u['id']; ?>" class="text-danger">Remove</a></td>
                     </tr>
                  <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <br><a href="index.php">Back to Dashboard</a>
    </div>





    

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>