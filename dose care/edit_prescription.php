<?php
include 'header.php';

$patient_id = $_GET['patient_id'] ?? null;
$rx_data = []; // Array to hold prescription data if it exists

// 2. FETCH EXISTING DATA (To pre-fill the form)
if ($patient_id) {
    // Get Patient Name for header
    $stmtP = $pdo->prepare("SELECT name FROM patients WHERE id = ?");
    $stmtP->execute([$patient_id]);
    $patient = $stmtP->fetch();

    // Get Latest Prescription to edit
    $stmtRx = $pdo->prepare("SELECT * FROM prescriptions WHERE patient_id = ? ORDER BY id DESC LIMIT 1");
    $stmtRx->execute([$patient_id]);
    $rx_data = $stmtRx->fetch();
}

// 3. PROCESS FORM SUBMISSION (UPDATE Request)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pid = $_POST['patient_id'];
    $rx_id = $_POST['rx_id']; 
    $medication = $_POST['medication_name'];
    $dosage = $_POST['dosage'];
    $frequency = $_POST['frequency'];
    $duration = $_POST['duration']; // CHANGED: Now getting this from the form
    $instructions = $_POST['instructions'];

    if (empty($medication) || empty($dosage)) {
        $error = "Please fill in required fields.";
    } else {
        try {
            if ($rx_id) {
                // Update existing prescription (CHANGED: Added duration here)
                $sql = "UPDATE prescriptions SET medication_name=?, dosage=?, frequency=?, duration=?, instructions=? WHERE id=?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$medication, $dosage, $frequency, $duration, $instructions, $rx_id]);
            } else {
                // If no existing rx, insert a new one
                $sql = "INSERT INTO prescriptions (patient_id, medication_name, dosage, frequency, duration, instructions) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$pid, $medication, $dosage, $frequency, $duration, $instructions]);
            }

            // Redirect back to patient details
            header("Location: patient_details.php?id=$pid&msg=rx_updated");
            exit();

        } catch (PDOException $e) {
            die("Error updating: " . $e->getMessage());
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Prescription - DoseCare</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
</head>
<body class="bg-light">
 
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white fw-bold">
                        Update Prescription for: <?php echo htmlspecialchars($patient['name'] ?? 'Patient'); ?>
                    </div>
                    <div class="card-body">
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patient_id); ?>">
                            <input type="hidden" name="rx_id" value="<?php echo htmlspecialchars($rx_data['id'] ?? ''); ?>">

                            <div class="mb-3">
                                <label class="form-label">Medication Name</label>
                                <input type="text" name="medication_name" class="form-control" value="<?php echo htmlspecialchars($rx_data['medication_name'] ?? ''); ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Dosage</label>
                                    <input type="text" name="dosage" class="form-control" value="<?php echo htmlspecialchars($rx_data['dosage'] ?? ''); ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Frequency</label>
                                    <select name="frequency" class="form-select" required>
                                        <?php $freq = $rx_data['frequency'] ?? ''; ?>
                                        <option value="">Select...</option>
                                        <option value="Once Daily" <?php if($freq == 'Once Daily') echo 'selected'; ?>>Once Daily</option>
                                        <option value="Twice Daily" <?php if($freq == 'Twice Daily') echo 'selected'; ?>>Twice Daily</option>
                                        <option value="Thrice Daily" <?php if($freq == 'Thrice Daily') echo 'selected'; ?>>Thrice Daily</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Duration</label>
                                    <input type="text" name="duration" class="form-control" placeholder="e.g. 1 Month" value="<?php echo htmlspecialchars($rx_data['duration'] ?? ''); ?>" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Special Instructions</label>
                                <textarea name="instructions" class="form-control" rows="3"><?php echo htmlspecialchars($rx_data['instructions'] ?? ''); ?></textarea>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="patient_details.php?id=<?php echo $patient_id; ?>" class="btn btn-outline-secondary">Cancel</a>
                                <button type="submit" class="btn btn-primary" style="background-color: #565e8b; border: none;">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php include 'footer.php'; ?>