<?php
// ============================================
// TCH Medical Center - Delete Patient
// ============================================
$base_url   = '../';
$page_title = 'Delete Patient';
require_once '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch patient
$res = $conn->query("SELECT * FROM patients WHERE id = $id");
if ($res->num_rows === 0) {
    header('Location: index.php');
    exit;
}
$patient = $res->fetch_assoc();

// Handle confirmed deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $conn->query("DELETE FROM patients WHERE id = $id");
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
                Delete Patient
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo;
                <a href="index.php">Patients</a> &rsaquo;
                Delete Patient
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary">
            &#8592; Back to Patients
        </a>
    </div>

    <!-- Delete Confirmation Card -->
    <div class="delete-confirm-card">
        <div class="delete-header">
            <span class="delete-icon">&#128465;</span>
            <h2>Confirm Deletion</h2>
        </div>
        <div class="delete-body">
            <p>You are about to permanently delete the following patient record:</p>
            <div class="record-name">
                &#128101; <?php echo htmlspecialchars($patient['full_name']); ?>
            </div>
            <p style="margin-top:8px;">
                <span class="badge <?php echo strtolower($patient['gender']) === 'male' ? 'badge-male' : (strtolower($patient['gender']) === 'female' ? 'badge-female' : 'badge-other'); ?>">
                    <?php echo $patient['gender']; ?>
                </span>
                &nbsp;&bull;&nbsp;
                DOB: <?php echo date('F j, Y', strtotime($patient['date_of_birth'])); ?>
            </p>
            <div class="alert alert-danger" style="margin-top:16px; text-align:left;">
                &#9888; <strong>Warning:</strong> This action cannot be undone.
                All associated appointments and medical records will also be deleted.
            </div>
        </div>
        <div class="delete-actions">
            <form method="POST" action="delete.php?id=<?php echo $id; ?>">
                <input type="hidden" name="confirm_delete" value="1">
                <button type="submit" class="btn btn-danger">
                    &#128465; Yes, Delete Patient
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
