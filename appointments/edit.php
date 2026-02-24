<?php
// ============================================
// TCH Medical Center - Edit Appointment
// ============================================
$base_url   = '../';
$page_title = 'Edit Appointment';
require_once '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch existing appointment with joined names
$res = $conn->query("
    SELECT a.*, p.full_name AS patient_name, d.full_name AS doctor_name
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN doctors  d ON a.doctor_id  = d.id
    WHERE a.id = $id
");
if ($res->num_rows === 0) {
    header('Location: index.php');
    exit;
}
$appt = $res->fetch_assoc();

$errors           = [];
$patient_id       = $appt['patient_id'];
$doctor_id        = $appt['doctor_id'];
$appointment_date = date('Y-m-d\TH:i', strtotime($appt['appointment_date']));

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

        $sql = "UPDATE appointments SET patient_id=$pid, doctor_id=$did, appointment_date='$date' WHERE id=$id";
        if ($conn->query($sql)) {
            header('Location: index.php?success=updated');
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
                <div class="page-icon">&#9998;</div>
                Edit Appointment
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo;
                <a href="index.php">Appointments</a> &rsaquo;
                Edit Appointment
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

    <!-- Info Alert -->
    <div class="alert alert-info">
        &#9432; Editing Appointment ID: <strong>#<?php echo $id; ?></strong>
        &mdash; <strong><?php echo htmlspecialchars($appt['patient_name']); ?></strong>
        with <strong><?php echo htmlspecialchars($appt['doctor_name']); ?></strong>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="card-header">
            <h2>&#128197; Update Appointment Details</h2>
        </div>
        <div class="form-body">
            <form method="POST" action="edit.php?id=<?php echo $id; ?>">

                <div class="form-group">
                    <label for="patient_id">
                        Patient <span class="required">*</span>
                    </label>
                    <select id="patient_id" name="patient_id" class="form-control" required>
                        <option value="">-- Select Patient --</option>
                        <?php
                        $patients->data_seek(0);
                        while ($p = $patients->fetch_assoc()):
                        ?>
                            <option value="<?php echo $p['id']; ?>"
                                <?php echo $patient_id == $p['id'] ? 'selected' : ''; ?>>
                                #<?php echo $p['id']; ?> &mdash; <?php echo htmlspecialchars($p['full_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
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
                </div>

                <div class="form-group">
                    <label for="appointment_date">
                        Appointment Date &amp; Time <span class="required">*</span>
                    </label>
                    <input type="datetime-local" id="appointment_date" name="appointment_date"
                           class="form-control"
                           value="<?php echo htmlspecialchars($appointment_date); ?>"
                           required>
                </div>

                <!-- Timestamps (read-only) -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Scheduled On</label>
                        <input type="text" class="form-control"
                               value="<?php echo date('F j, Y h:i A', strtotime($appt['created_at'])); ?>"
                               readonly style="background:#f4f6f9; color:var(--text-muted);">
                    </div>
                    <div class="form-group">
                        <label>Last Updated</label>
                        <input type="text" class="form-control"
                               value="<?php echo date('F j, Y h:i A', strtotime($appt['updated_at'])); ?>"
                               readonly style="background:#f4f6f9; color:var(--text-muted);">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        &#10003; Update Appointment
                    </button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <a href="delete.php?id=<?php echo $id; ?>"
                       class="btn btn-danger"
                       style="margin-left:auto;">
                        &#128465; Delete This Appointment
                    </a>
                </div>

            </form>
        </div>
    </div>

</div>

<?php
$conn->close();
require_once '../includes/footer.php';
?>
