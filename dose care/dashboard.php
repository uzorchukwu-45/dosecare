<?php     include 'header.php'; ?>


<?php


// Check Authentication (FR3)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['full_name'];

// Logic for sending Notifications (FR9)
// Note: You must install PHPMailer via Composer or download the files
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function sendAdherenceAlert($toEmail, $patientName) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com'; // Use your SMTP provider
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your-email@gmail.com';
        $mail->Password   = 'your-app-password'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom('system@parklane.com', 'DoseCare System');
        $mail->addAddress($toEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Medication Reminder - DoseCare';
        $mail->Body    = "Hello $patientName, this is a reminder to take your scheduled medication.";
        $mail->send();
    } catch (Exception $e) {
        error_log("Mail Error: {$mail->ErrorInfo}");
    }
}

// Fetch Adherence Data (FR13)
$stmt = $pdo->prepare("SELECT status FROM adherence_logs WHERE patient_id = ?");
$stmt->execute([$user_id]);
$logs = $stmt->fetchAll();
$total_doses = count($logs);
$taken_doses = count(array_filter($logs, fn($l) => $l['status'] == 'YES'));
$adherence_rate = ($total_doses > 0) ? round(($taken_doses / $total_doses) * 100) : 0;

// Fetch Prescriptions (FR6)
$p_stmt = $pdo->prepare("SELECT * FROM prescriptions WHERE patient_id = ?");
$p_stmt->execute([$user_id]);
$prescriptions = $p_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>DoseCare Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>
    <div class="dashboard-container">
    <div class="sidebar">
        <h2>DoseCare</h2>
        <p>User: <?php echo htmlspecialchars($user_name); ?></p>
      
    </div>

    <div class="main-content">
        <header>
            <h1>Outpatient Adherence Portal</h1>
        </header>

        <section class="stat-container">
            <div class="card">
                <h3>Overall Adherence Rate</h3>
                <div class="progress-ring">
                    <span class="percentage"><?php echo $adherence_rate; ?>%</span>
                </div>
                <?php if ($adherence_rate < 60): ?>
                    <p style="color:red;">Alert: Below 60% threshold (FR14)</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="table-section">
            <h3>Active Prescriptions</h3>
            <table>
                <thead>
                    <tr>
                        <th>Medication</th>
                        <th>Dosage</th>
                        <th>Frequency</th>
                        <th>Duration</th>
                        <th>instruction</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prescriptions as $med): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($med['medication_name']); ?></td>
                        <td><?php echo htmlspecialchars($med['dosage']); ?></td>
                        <td><?php echo htmlspecialchars($med['frequency']); ?></td>
                         <td><?php echo htmlspecialchars($med['duration']); ?></td>
                          <td><?php echo htmlspecialchars($med['instruction']); ?></td>
                        <td>
                            <form method="POST" action="log_adherence.php">
                                <input type="hidden" name="p_id" value="<?php echo $med['id']; ?>">
                                <button type="submit" name="status" value="YES" class="btn-yes">Taken</button>
                                <button type="submit" name="status" value="NO" class="btn-no">Missed</button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>
</div>
</body>
</html>

<?php    include 'footer.php';   ?>







