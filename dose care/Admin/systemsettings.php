<?php
session_start();
require_once '../config.php';

// Security Check
if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: adminlogin.php"); 
    exit(); 
}

// 1. Handle Form Submission (Update Settings)
if (isset($_POST['save_settings'])) {
    $gateway = $_POST['gateway'];
    $reminder_time = $_POST['reminder_time'];

    try {
        // Update the single row (ID = 1) in the settings table
        $stmt = $pdo->prepare("UPDATE settings SET gateway = ?, reminder_time = ? WHERE id = 1");
        $stmt->execute([$gateway, $reminder_time]);
        
        $success_msg = "Configuration saved successfully!";
        // ADD THIS LINE:
    logSystemActivity($pdo, 'Admin', $_SESSION['admin_id'] ?? 'SuperAdmin', 'Update Settings', "Changed SMS Gateway to $gateway");
    } catch (PDOException $e) {
        $error_msg = "Error saving settings: " . $e->getMessage();
    }
}

// 2. Fetch Current Settings (Read from Database)
// We use ID=1 because there is only one global setting for the system
$stmt = $pdo->query("SELECT * FROM settings WHERE id = 1");
$current_settings = $stmt->fetch(PDO::FETCH_ASSOC);

// Fallback defaults if database is empty
$saved_gateway = $current_settings['gateway'] ?? 'Termii';
$saved_time = $current_settings['reminder_time'] ?? '08:00';
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <title>System Settings - DoseCare</title>
  </head>

  <body class="admin-wrapper">
    <div class="main-content">
        <h2>System Settings (FR22)</h2>
        
        <?php if(isset($success_msg)) echo "<div class='alert alert-success'>$success_msg</div>"; ?>
        <?php if(isset($error_msg)) echo "<div class='alert alert-danger'>$error_msg</div>"; ?>

        <div class="stat-card">
            <form method="POST">
                <label>SMS Gateway Integration (Termii/Twilio):</label><br>
                <select name="gateway" style="padding: 10px; margin: 10px 0; width: 100%; max-width: 300px;">
                    <option value="Termii" <?php if($saved_gateway == 'Termii') echo 'selected'; ?>>Termii API</option>
                    <option value="Twilio" <?php if($saved_gateway == 'Twilio') echo 'selected'; ?>>Twilio API</option>
                </select><br>

                <label>Daily Reminder Time:</label><br>
                <input type="time" name="reminder_time" value="<?php echo htmlspecialchars($saved_time); ?>" style="padding: 10px; margin: 10px 0;"><br><br>

                <button type="submit" name="save_settings" class="btn-msg">Save Configuration</button>
            </form>
        </div>
        
        <br>
        <a href="index.php">Back to Dashboard</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>