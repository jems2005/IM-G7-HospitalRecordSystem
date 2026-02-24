<?php
// ============================================
// TCH Medical Center - Add Medical Record
// ============================================
$base_url   = '../';
$page_title = 'Add Medical Record';
require_once '../config/database.php';

$errors     = [];
$patient_id = '';
$diagnosis  = '';
$treatment  = '';

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

        $sql = "INSERT INTO medical_records (patient_id, diagnosis, treatment) VALUES ($pid, '$diag', '$trt')";
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
                <div class="page-icon">&#128203;</div>
                Add Medical Record
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo;
                <a href="index.php">Medical Records</a> &rsaquo;
                Add Medical Record
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

    <!-- Form Card -->
    <div class="form-card">
        <div class="card-header">
            <h2>&#128203; Medical Record Details</h2>
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
                    <label for="diagnosis">
                        Diagnosis <span class="required">*</span>
                    </label>
                    <input type="text" id="diagnosis" name="diagnosis"
                           class="form-control"
                           placeholder="e.g. Hypertension, Type 2 Diabetes, Fracture..."
                           value="<?php echo htmlspecialchars($diagnosis); ?>"
                           required>
                    <div class="form-hint">Enter the primary diagnosis or condition.</div>
                </div>

                <div class="form-group">
                    <label for="treatment">
                        Treatment <span class="required">*</span>
                    </label>
                    <textarea id="treatment" name="treatment"
                              class="form-control"
                              placeholder="Describe the prescribed treatment, medications, dosage, and instructions..."
                              rows="5"
                              required><?php echo htmlspecialchars($treatment); ?></textarea>
                    <div class="form-hint">Include medications, dosage, therapy, and follow-up instructions.</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        &#10003; Save Medical Record
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
