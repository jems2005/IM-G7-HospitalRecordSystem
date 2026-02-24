<?php
// ============================================
// TCH Medical Center - Delete Appointment
// ============================================
$base_url   = '../';
$page_title = 'Delete Appointment';
require_once '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch appointment with joined names
$res = $conn->query("
    SELECT a.*, p.full_name AS patient_name, d.full_name AS doctor_name, d.specialization
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

// Handle confirmed deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $conn->query("DELETE FROM appointments WHERE id = $id");
    header('Location: index.php?success=deleted');
    exit;
}

require_once '../includes/header.php';
require_once '../includes/navbar.php';
?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>
                <div class="page-icon">&#128465;</div>
                Delete Appointment
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo;
                <a href="index.php">Appointments</a> &rsaquo;
                Delete Appointment
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary">
            &#8592; Back to Appointments
        </a>
    </div>

    <!-- Delete Confirmation Card -->
    <div class="delete-confirm-card">
        <div class="delete-header">
            <span class="delete-icon">&#128197;</span>
            <h2>Confirm Deletion</h2>
        </div>
        <div class="delete-body">
            <p>You are about to permanently delete the following appointment:</p>
            <div class="record-name">
                &#128101; <?php echo htmlspecialchars($appt['patient_name']); ?>
            </div>
            <p style="margin-top:8px;">
                <strong>Doctor:</strong> <?php echo htmlspecialchars($appt['doctor_name']); ?>
                &nbsp;&bull;&nbsp;
                <span class="badge badge-info"><?php echo htmlspecialchars($appt['specialization']); ?></span>
            </p>
            <p style="margin-top:6px;">
                <strong>Date:</strong>
                <?php echo date('F j, Y \a\t h:i A', strtotime($appt['appointment_date'])); ?>
            </p>
            <div class="alert alert-danger" style="margin-top:16px; text-align:left;">
                &#9888; <strong>Warning:</strong> This action cannot be undone.
            </div>
        </div>
        <div class="delete-actions">
            <form method="POST" action="delete.php?id=<?php echo $id; ?>">
                <input type="hidden" name="confirm_delete" value="1">
                <button type="submit" class="btn btn-danger">
                    &#128465; Yes, Delete Appointment
                </button>
            </form>
            <a href="index.php" class="btn btn-secondary">
                &#10005; Cancel
            </a>
        </div>
    </div>

</div>

<?php
$conn->close();
require_once '../includes/footer.php';
?>
