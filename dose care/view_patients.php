<?php
include 'header.php';



// Include your database connection file (e.g., config.php or db.php)
// include 'db_connect.php'; 

if (!isset($_SESSION['full_name'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DoseCare | View Patients</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Outpatient Adherence List</h2>
        <a href="doctor_dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-primary">
                    <tr>
                        <th>Patient ID</th>
                        <th>Name</th>
                        <th>Condition</th>
                        <th>Adherence Rate</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>PLH-102</td>
                        <td>John Doe</td>
                        <td>Hypertension</td>
                        <td>
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 85%;">85%</div>
                            </div>
                        </td>
                        <td><span class="badge bg-success">Stable</span></td>
                        <td><a href="patient_details.php?id=102" class="btn btn-sm btn-primary">View History</a></td>
                    </tr>
                    </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>