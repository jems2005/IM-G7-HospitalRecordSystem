<?php
// ============================================
// TCH Medical Center - Schedule Appointment
// ============================================
$base_url   = '../';
$page_title = 'Schedule Appointment';
require_once '../config/database.php';

$errors           = [];
$patient_id       = '';
$doctor_id        = '';
$appointment_date = '';

// Fetch all patients and doctors for dropdowns
$patients = $conn->query("SELECT id, full_name FROM patients ORDER BY full_name ASC");
$doctors  = $conn->query("SELECT id, full_name, specialization FROM doctors ORDER BY full_name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id       = trim($_POST['patient_id'] ?? '');
    $doctor_id        = trim($_POST['doctor_id'] ?? '');
    $appointment_date = trim($_POST['appointment_date'] ?? '');

    // Validation
    if (empty($patient_id))       $errors[] = 'Please select a patient.';
    if (empty($doctor_id))        $errors[] = 'Please select a doctor.';
    if (empty($appointment_date)) $errors[] = 'Appointment date and time is required.';

    if (empty($errors)) {
        $pid  = (int)$patient_id;
        $did  = (int)$doctor_id;
        $date = $conn->real_escape_string($appointment_date);

        $sql = "INSERT INTO appointments (patient_id, doctor_id, appointment_date) VALUES ($pid, $did, '$date')";
        if ($conn->query($sql)) {
            header('Location: index.php?success=added');
            exit;
        } else {
            $errors[] = 'Database error: ' . $conn->error;
        }
    }
}

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>
                <div class="page-icon">&#128197;</div>
                Schedule Appointment
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo;
                <a href="index.php">Appointments</a> &rsaquo;
                Schedule Appointment
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary">
            &#8592; Back to Appointments
        </a>
    </div>

    <!-- Error Messages -->
    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <div>
                <strong>&#9888; Please fix the following errors:</strong>
                <ul style="margin-top:6px; padding-left:18px;">
                    <?php foreach ($errors as $e): ?>
                        <li><?php echo htmlspecialchars($e); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form Card -->
    <div class="form-card">
        <div class="card-header">
            <h2>&#128197; Appointment Details</h2>
        </div>
        <div class="form-body">
            <form method="POST" action="create.php">

                <div class="form-group">
                    <label for="patient_id">
                        Patient <span class="required">*</span>
                    </label>
                    <select id="patient_id" name="patient_id" class="form-control" required>
                        <option value="">-- Select Patient --</option>
                        <?php
                        // Reset pointer
                        $patients->data_seek(0);
                        while ($p = $patients->fetch_assoc()):
                        ?>
                            <option value="<?php echo $p['id']; ?>"
                                <?php echo $patient_id == $p['id'] ? 'selected' : ''; ?>>
                                #<?php echo $p['id']; ?> &mdash; <?php echo htmlspecialchars($p['full_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <div class="form-hint">
                        Patient not listed? <a href="../patients/create.php">Add a new patient</a>.
                    </div>
                </div>

                <div class="form-group">
                    <label for="doctor_id">
                        Doctor <span class="required">*</span>
                    </label>
                    <select id="doctor_id" name="doctor_id" class="form-control" required>
                        <option value="">-- Select Doctor --</option>
                        <?php
                        $doctors->data_seek(0);
                        while ($d = $doctors->fetch_assoc()):
                        ?>
                            <option value="<?php echo $d['id']; ?>"
                                <?php echo $doctor_id == $d['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($d['full_name']); ?>
                                (<?php echo htmlspecialchars($d['specialization']); ?>)
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <div class="form-hint">
                        Doctor not listed? <a href="../doctors/create.php">Add a new doctor</a>.
                    </div>
                </div>

                <div class="form-group">
                    <label for="appointment_date">
                        Appointment Date &amp; Time <span class="required">*</span>
                    </label>
                    <input type="datetime-local" id="appointment_date" name="appointment_date"
                           class="form-control"
                           value="<?php echo htmlspecialchars($appointment_date); ?>"
                           required>
                    <div class="form-hint">Select the scheduled date and time for the appointment.</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        &#10003; Schedule Appointment
                    </button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>

</div>

<?php
$conn->close();
require_once '../includes/footer.php';
?>
