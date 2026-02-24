<?php
// ============================================
// TCH Medical Center - Add New Doctor
// ============================================
$base_url   = '../';
$page_title = 'Add Doctor';
require_once '../config/database.php';

$errors         = [];
$full_name      = '';
$specialization = '';
$contact_number = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name      = trim($_POST['full_name'] ?? '');
    $specialization = trim($_POST['specialization'] ?? '');
    $contact_number = trim($_POST['contact_number'] ?? '');

    // Validation
    if (empty($full_name))      $errors[] = 'Full name is required.';
    if (empty($specialization)) $errors[] = 'Specialization is required.';
    if (empty($contact_number)) $errors[] = 'Contact number is required.';

    if (empty($errors)) {
        $fn  = $conn->real_escape_string($full_name);
        $sp  = $conn->real_escape_string($specialization);
        $cn  = $conn->real_escape_string($contact_number);

        $sql = "INSERT INTO doctors (full_name, specialization, contact_number) VALUES ('$fn', '$sp', '$cn')";
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
                Add New Doctor
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo;
                <a href="index.php">Doctors</a> &rsaquo;
                Add New Doctor
            </div>
        </div>
        <a href="index.php" class="btn btn-secondary">
            &#8592; Back to Doctors
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
            <h2>&#129658; Doctor Information</h2>
        </div>
        <div class="form-body">
            <form method="POST" action="create.php">

                <div class="form-group">
                    <label for="full_name">
                        Full Name <span class="required">*</span>
                    </label>
                    <input type="text" id="full_name" name="full_name"
                           class="form-control"
                           placeholder="e.g. Dr. Jose Rizal"
                           value="<?php echo htmlspecialchars($full_name); ?>"
                           required>
                    <div class="form-hint">Include title (e.g. Dr., Prof.)</div>
                </div>

                <div class="form-group">
                    <label for="specialization">
                        Specialization <span class="required">*</span>
                    </label>
                    <select id="specialization" name="specialization" class="form-control" required>
                        <option value="">-- Select Specialization --</option>
                        <?php
                        $specializations = [
                            'Cardiology', 'Dermatology', 'Emergency Medicine',
                            'Endocrinology', 'Gastroenterology', 'General Practice',
                            'Hematology', 'Internal Medicine', 'Nephrology',
                            'Neurology', 'Obstetrics & Gynecology', 'Oncology',
                            'Ophthalmology', 'Orthopedics', 'Otolaryngology',
                            'Pediatrics', 'Psychiatry', 'Pulmonology',
                            'Radiology', 'Rheumatology', 'Surgery', 'Urology'
                        ];
                        foreach ($specializations as $sp):
                        ?>
                            <option value="<?php echo $sp; ?>"
                                <?php echo $specialization === $sp ? 'selected' : ''; ?>>
                                <?php echo $sp; ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="Other" <?php echo $specialization === 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="contact_number">
                        Contact Number <span class="required">*</span>
                    </label>
                    <input type="text" id="contact_number" name="contact_number"
                           class="form-control"
                           placeholder="e.g. 09171234567"
                           value="<?php echo htmlspecialchars($contact_number); ?>"
                           required>
                    <div class="form-hint">Enter a valid Philippine mobile or landline number.</div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">
                        &#10003; Save Doctor
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
