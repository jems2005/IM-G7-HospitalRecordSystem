<?php
// ============================================
// TCH Medical Center - Edit Medical Record
// ============================================
$base_url   = '../';
$page_title = 'Edit Medical Record';
require_once '../config/database.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch existing record with patient name
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

$errors     = [];
$patient_id = $record['patient_id'];
$diagnosis  = $record['diagnosis'];
$treatment  = $record['treatment'];

// Fetch all patients for dropdown
$patients = $conn->query("SELECT id, full_name FROM patients ORDER BY full_name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = trim($_POST['patient_id'] ?? '');
    $diagnosis  = trim($_POST['diagnosis'] ?? '');
    $treatment  = trim($_POST['treatment'] ?? '');

    // Validation
    if (empty($patient_id)) $errors[] = 'Please select a patient.';
    if (empty($diagnosis))  $errors[] = 'Diagnosis is required.';
    if (empty($treatment))  $errors[] = 'Treatment details are required.';

    if (empty($errors)) {
        $pid  = (int)$patient_id;
        $diag = $conn->real_escape_string($diagnosis);
        $trt  = $conn->real_escape_string($treatment);

        $sql = "UPDATE medical_records SET patient_id=$pid, diagnosis='$diag', treatment='$trt' WHERE id=$id";
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
                Edit Medical Record
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo;
                <a href="index.php">Medical Records</a> &rsaquo;
                Edit Medical Record
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary">
            &#8592; Back to Medical Records
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
        &#9432; Editing Medical Record ID: <strong>#<?php echo $id; ?></strong>
        &mdash; Patient: <strong><?php echo htmlspecialchars($record['patient_name']); ?></strong>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="card-header">
            <h2>&#128203; Update Medical Record</h2>
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
                    <label for="diagnosis">
                        Diagnosis <span class="required">*</span>
                    </label>
                    <input type="text" id="diagnosis" name="diagnosis"
                           class="form-control"
                           placeholder="e.g. Hypertension, Type 2 Diabetes..."
                           value="<?php echo htmlspecialchars($diagnosis); ?>"
                           required>
                </div>

                <div class="form-group">
                    <label for="treatment">
                        Treatment <span class="required">*</span>
                    </label>
                    <textarea id="treatment" name="treatment"
                              class="form-control"
                              placeholder="Describe the prescribed treatment, medications, dosage..."
                              rows="5"
                              required><?php echo htmlspecialchars($treatment); ?></textarea>
                    <div class="form-hint">Include medications, dosage, therapy, and follow-up instructions.</div>
                </div>

                <!-- Timestamps (read-only) -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Date Recorded</label>
                        <input type="text" class="form-control"
                               value="<?php echo date('F j, Y h:i A', strtotime($record['created_at'])); ?>"
                               readonly style="background:#f4f6f9; color:var(--text-muted);">
                    </div>
                    <div class="form-group">
                        <label>Last Updated</label>
                        <input type="text" class="form-control"
                               value="<?php echo date('F j, Y h:i A', strtotime($record['updated_at'])); ?>"
                               readonly style="background:#f4f6f9; color:var(--text-muted);">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        &#10003; Update Medical Record
                    </button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <a href="delete.php?id=<?php echo $id; ?>"
                       class="btn btn-danger"
                       style="margin-left:auto;">
                        &#128465; Delete This Record
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
