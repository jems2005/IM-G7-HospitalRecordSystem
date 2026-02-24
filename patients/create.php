<?php
// ============================================
// TCH Medical Center - Add New Patient
// ============================================
$base_url   = '../';
$page_title = 'Add Patient';
require_once '../config/database.php';

$errors = [];
$full_name    = '';
$date_of_birth = '';
$gender       = '';

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

        $sql = "INSERT INTO patients (full_name, date_of_birth, gender) VALUES ('$fn', '$dob', '$g')";
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
                <div class="page-icon">&#10133;</div>
                Add New Patient
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo;
                <a href="index.php">Patients</a> &rsaquo;
                Add New Patient
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

    <!-- Form Card -->
    <div class="form-card">
        <div class="card-header">
            <h2>&#128101; Patient Information</h2>
        </div>
        <div class="form-body">
            <form method="POST" action="create.php">

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

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        &#10003; Save Patient
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
