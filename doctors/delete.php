<?php
// ============================================
// TCH Medical Center - Delete Doctor
// ============================================
$base_url   = '../';
$page_title = 'Delete Doctor';
require_once '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch doctor
$res = $conn->query("SELECT * FROM doctors WHERE id = $id");
if ($res->num_rows === 0) {
    header('Location: index.php');
    exit;
}
$doctor = $res->fetch_assoc();

// Count related appointments
$appt_count = $conn->query("SELECT COUNT(*) AS cnt FROM appointments WHERE doctor_id = $id")->fetch_assoc()['cnt'];

// Handle confirmed deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $conn->query("DELETE FROM doctors WHERE id = $id");
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
                Delete Doctor
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo;
                <a href="index.php">Doctors</a> &rsaquo;
                Delete Doctor
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary">
            &#8592; Back to Doctors
        </a>
    </div>

    <!-- Delete Confirmation Card -->
    <div class="delete-confirm-card">
        <div class="delete-header">
            <span class="delete-icon">&#128465;</span>
            <h2>Confirm Deletion</h2>
        </div>
        <div class="delete-body">
            <p>You are about to permanently delete the following doctor record:</p>
            <div class="record-name">
                &#129658; <?php echo htmlspecialchars($doctor['full_name']); ?>
            </div>
            <p style="margin-top:8px;">
                <span class="badge badge-info"><?php echo htmlspecialchars($doctor['specialization']); ?></span>
                &nbsp;&bull;&nbsp;
                <?php echo htmlspecialchars($doctor['contact_number']); ?>
            </p>
            <?php if ($appt_count > 0): ?>
                <div class="alert alert-warning" style="margin-top:16px; text-align:left;">
                    &#9888; This doctor has <strong><?php echo $appt_count; ?> appointment(s)</strong>
                    that will also be deleted.
                </div>
            <?php endif; ?>
            <div class="alert alert-danger" style="margin-top:12px; text-align:left;">
                &#9888; <strong>Warning:</strong> This action cannot be undone.
            </div>
        </div>
        <div class="delete-actions">
            <form method="POST" action="delete.php?id=<?php echo $id; ?>">
                <input type="hidden" name="confirm_delete" value="1">
                <button type="submit" class="btn btn-danger">
                    &#128465; Yes, Delete Doctor
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
