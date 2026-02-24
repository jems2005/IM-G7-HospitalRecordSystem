<?php
// ============================================
// TCH Medical Center - Edit Patient
// ============================================
$base_url   = '../';
$page_title = 'Edit Patient';
require_once '../config/database.php';

// Get patient ID
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
    header('Location: index.php');
    exit;
}

// Fetch existing patient
$res = $conn->query("SELECT * FROM patients WHERE id = $id");
if ($res->num_rows === 0) {
    header('Location: index.php');
    exit;
}
$patient = $res->fetch_assoc();

$errors = [];
$full_name     = $patient['full_name'];
$date_of_birth = $patient['date_of_birth'];
$gender        = $patient['gender'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name     = trim($_POST['full_name'] ?? '');
    $date_of_birth = trim($_POST['date_of_birth'] ?? '');
    $gender        = trim($_POST['gender'] ?? '');

    // Validation
    if (empty($full_name))     $errors[] = 'Full name is required.';
    if (empty($date_of_birth)) $errors[] = 'Date of birth is required.';
    if (empty($gender))        $errors[] = 'Gender is required.';

    if (empty($errors)) {
        $fn  = $conn->real_escape_string($full_name);
        $dob = $conn->real_escape_string($date_of_birth);
        $g   = $conn->real_escape_string($gender);

        $sql = "UPDATE patients SET full_name='$fn', date_of_birth='$dob', gender='$g' WHERE id=$id";
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
                Edit Patient
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo;
                <a href="index.php">Patients</a> &rsaquo;
                Edit Patient
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary">
            &#8592; Back to Patients
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
        &#9432; Editing record for Patient ID: <strong>#<?php echo $id; ?></strong>
        &mdash; <strong><?php echo htmlspecialchars($patient['full_name']); ?></strong>
    </div>

    <!-- Form Card -->
    <div class="form-card">
        <div class="card-header">
            <h2>&#128101; Update Patient Information</h2>
        </div>
        <div class="form-body">
            <form method="POST" action="edit.php?id=<?php echo $id; ?>">

                <div class="form-group">
                    <label for="full_name">
                        Full Name <span class="required">*</span>
                    </label>
                    <input type="text" id="full_name" name="full_name"
                           class="form-control"
                           placeholder="e.g. Juan dela Cruz"
                           value="<?php echo htmlspecialchars($full_name); ?>"
                           required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="date_of_birth">
                            Date of Birth <span class="required">*</span>
                        </label>
                        <input type="date" id="date_of_birth" name="date_of_birth"
                               class="form-control"
                               value="<?php echo htmlspecialchars($date_of_birth); ?>"
                               max="<?php echo date('Y-m-d'); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="gender">
                            Gender <span class="required">*</span>
                        </label>
                        <select id="gender" name="gender" class="form-control" required>
                            <option value="">-- Select Gender --</option>
                            <option value="Male"   <?php echo $gender === 'Male'   ? 'selected' : ''; ?>>Male</option>
                            <option value="Female" <?php echo $gender === 'Female' ? 'selected' : ''; ?>>Female</option>
                            <option value="Other"  <?php echo $gender === 'Other'  ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                </div>

                <!-- Timestamps (read-only display) -->
                <div class="form-row">
                    <div class="form-group">
                        <label>Registered On</label>
                        <input type="text" class="form-control"
                               value="<?php echo date('F j, Y h:i A', strtotime($patient['created_at'])); ?>"
                               readonly style="background:#f4f6f9; color:var(--text-muted);">
                    </div>
                    <div class="form-group">
                        <label>Last Updated</label>
                        <input type="text" class="form-control"
                               value="<?php echo date('F j, Y h:i A', strtotime($patient['updated_at'])); ?>"
                               readonly style="background:#f4f6f9; color:var(--text-muted);">
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-success">
                        &#10003; Update Patient
                    </button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                    <a href="delete.php?id=<?php echo $id; ?>"
                       class="btn btn-danger"
                       style="margin-left:auto;">
                        &#128465; Delete This Patient
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
