<?php
session_start();
include '../config.php'; 

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: adminlogin.php");
    exit();
}

$staff_count = 0;
$patient_count = 0;
$critical_patients = [];

try {
    $staff_query = "SELECT COUNT(*) FROM users WHERE role IN ('Doctor', 'Nurse', 'Pharmacist')";
    $staff_count = $pdo->query($staff_query)->fetchColumn();

    $patient_query = "SELECT COUNT(*) FROM users WHERE role = 'Patient'";
    $patient_count = $pdo->query($patient_query)->fetchColumn();

    $critical_stmt = $pdo->query("SELECT u.full_name, 
        (COUNT(CASE WHEN l.status = 'YES' THEN 1 END) * 100 / COUNT(l.id)) as rate 
        FROM adherence_logs l 
        JOIN users u ON l.patient_id = u.id 
        GROUP BY u.id HAVING rate < 60 LIMIT 5");
    $critical_patients = $critical_stmt->fetchAll();
} catch (Exception $e) {
    error_log($e->getMessage());
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        /* Internal CSS for Sidebar Toggle */
        .admin-wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        .sidebar {
            min-width: 250px;
            max-width: 250px;
            background: #2c3e50;
            color: #fff;
            transition: all 0.3s;
            min-height: 100vh;
        }

        /* The class that hides the sidebar */
        .sidebar.collapsed {
            margin-left: -250px;
        }

        .main-content {
            width: 100%;
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
        }

        .nav-links {
            list-style: none;
            padding: 0;
        }

        .nav-links li a {
            padding: 15px;
            display: block;
            color: white;
            text-decoration: none;
        }

        .nav-links li a:hover {
            background: #34495e;
        }

        #sidebarToggle {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }
            .sidebar.collapsed {
                margin-left: 0;
            }
        }
    </style>

    <title>DoseCare Admin | Dashboard</title>
  </head>
  <body>

    <div class="admin-wrapper">
        <nav class="sidebar" id="sidebar">
            <div class="sidebar-header p-4">
                <h3>DoseCare Admin</h3>
            </div>
            <ul class="nav-links">
                <li class="active"><a href="index.php"><i class="fas fa-chart-pie"></i> System Overview</a></li>
                <li><a href="manageusers.php"><i class="fas fa-users"></i> User Management</a></li>
                <li><a href="systemsettings.php"><i class="fas fa-cog"></i> System Settings</a></li>
                <li><a href="systemlogs.php"><i class="fas fa-history"></i> System Logs</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </nav>

        <main class="main-content">
            <header class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex align-items-center">
                    <button id="sidebarToggle" class="me-3">
                        <i class="fas fa-bars"></i>
                    </button>
                    <h1 class="h3 mb-0">Parklane Hospital Management</h1>
                </div>
                <div class="admin-profile">
                    <strong>Admin:</strong> <?php echo htmlspecialchars($_SESSION['admin_username']); ?>
                </div>
            </header>

            <section class="row mb-4">
                <div class="col-md-4">
                    <div class="card p-4 shadow-sm">
                        <h5>Total Hospital Staff</h5>
                        <h2 class="text-primary"><?php echo $staff_count; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 shadow-sm">
                        <h5>Registered Patients</h5>
                        <h2 class="text-success"><?php echo $patient_count; ?></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card p-4 shadow-sm bg-light">
                        <h5>Critical Alerts</h5>
                        <h2 class="text-danger"><?php echo count($critical_patients); ?></h2>
                    </div>
                </div>
            </section>

            <section class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Critical Adherence Monitoring (Below 60%)</h5>
                </div>
                <div class="table-responsive p-3">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Patient Name</th>
                                <th>Current Rate</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($critical_patients) > 0): ?>
                                <?php foreach($critical_patients as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['full_name']); ?></td>
                                    <td class="text-danger fw-bold"><?php echo round($p['rate']); ?>%</td>
                                    <td><span class="badge bg-danger">Low Adherence</span></td>
                                    <td><button class="btn btn-sm btn-warning">Alert Doctor</button></td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center">No alerts found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');

            toggleBtn.addEventListener('click', function() {
                // Toggles the .collapsed class to show/hide
                sidebar.classList.toggle('collapsed');
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>