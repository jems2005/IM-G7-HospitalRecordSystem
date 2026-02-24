<?php
// ============================================
// TCH Medical Center - Delete Medical Record
// ============================================
$base_url   = '../';
$page_title = 'Delete Medical Record';
require_once '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch record with patient name
$res = $conn->query("
    SELECT mr.*, p.full_name AS patient_name
    FROM medical_records mr
    JOIN patients p ON mr.patient_id = p.id
    WHERE mr.id = $id
");
if ($res->num_rows === 0) {
    header('Location: index.php');
    exit;
}
$record = $res->fetch_assoc();

// Handle confirmed deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_delete'])) {
    $conn->query("DELETE FROM medical_records WHERE id = $id");
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
                Delete Medical Record
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo;
                <a href="index.php">Medical Records</a> &rsaquo;
                Delete Medical Record
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary">
            &#8592; Back to Medical Records
        </a>
    </div>

    <!-- Delete Confirmation Card -->
    <div class="delete-confirm-card">
        <div class="delete-header">
            <span class="delete-icon">&#128203;</span>
            <h2>Confirm Deletion</h2>
        </div>
        <div class="delete-body">
            <p>You are about to permanently delete the following medical record:</p>
            <div class="record-name">
                &#128101; <?php echo htmlspecialchars($record['patient_name']); ?>
            </div>
            <p style="margin-top:10px;">
                <strong>Diagnosis:</strong>
                <span class="badge badge-danger" style="margin-left:6px;">
                    <?php echo htmlspecialchars($record['diagnosis']); ?>
                </span>
            </p>
            <p style="margin-top:8px; font-size:13px; color:var(--text-muted); text-align:left; padding:0 10px;">
                <strong>Treatment:</strong>
                <?php echo htmlspecialchars(substr($record['treatment'], 0, 120)) . (strlen($record['treatment']) > 120 ? '...' : ''); ?>
            </p>
            <p style="margin-top:8px; font-size:12px; color:var(--text-muted);">
                Recorded on: <?php echo date('F j, Y h:i A', strtotime($record['created_at'])); ?>
            </p>
            <div class="alert alert-danger" style="margin-top:16px; text-align:left;">
                &#9888; <strong>Warning:</strong> This action cannot be undone.
            </div>
        </div>
        <div class="delete-actions">
            <form method="POST" action="delete.php?id=<?php echo $id; ?>">
                <input type="hidden" name="confirm_delete" value="1">
                <button type="submit" class="btn btn-danger">
                    &#128465; Yes, Delete Record
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
