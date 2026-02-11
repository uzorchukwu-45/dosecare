<?php
session_start();
require_once '../config.php';

// Security Check
if (!isset($_SESSION['admin_logged_in'])) { 
    header("Location: adminlogin.php"); 
    exit(); 
}

// Fetch Logs (Newest first)
$stmt = $pdo->query("SELECT * FROM system_logs ORDER BY created_at DESC");
$logs = $stmt->fetchAll();
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="admin.css">
    <title>System Logs - DoseCare</title>
  </head>

  <body class="admin-wrapper">
    <div class="main-content">
        <h2>System Logs (FR23)</h2>
        <p class="text-muted">Audit trail of all system activities and security alerts.</p>
        
        <div class="data-table card p-3">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Time</th>
                        <th>User Type</th>
                        <th>User ID</th>
                        <th>Action</th>
                        <th>Details</th>
                        <th>IP Address</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($logs) > 0): ?>
                        <?php foreach ($logs as $log): ?>
                        <tr>
                            <td><?php echo date('M d, H:i A', strtotime($log['created_at'])); ?></td>
                            <td>
                                <span class="badge bg-secondary"><?php echo htmlspecialchars($log['user_type']); ?></span>
                            </td>
                            <td><strong><?php echo htmlspecialchars($log['user_id']); ?></strong></td>
                            <td class="text-primary"><?php echo htmlspecialchars($log['action']); ?></td>
                            <td><?php echo htmlspecialchars($log['details']); ?></td>
                            <td class="text-muted small"><?php echo htmlspecialchars($log['ip_address']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No system logs found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <br>
        <a href="index.php" class="btn btn-outline-dark">Back to Dashboard</a>
    </div>
  </body>
</html>