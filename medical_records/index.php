<?php
// ============================================
// TCH Medical Center - Medical Records List
// ============================================
$base_url   = '../';
$page_title = 'Medical Records';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $s      = $conn->real_escape_string($search);
    $result = $conn->query("
        SELECT mr.*, p.full_name AS patient_name
        FROM medical_records mr
        JOIN patients p ON mr.patient_id = p.id
        WHERE p.full_name LIKE '%$s%' OR mr.diagnosis LIKE '%$s%' OR mr.treatment LIKE '%$s%'
        ORDER BY mr.created_at DESC
    ");
} else {
    $result = $conn->query("
        SELECT mr.*, p.full_name AS patient_name
        FROM medical_records mr
        JOIN patients p ON mr.patient_id = p.id
        ORDER BY mr.created_at DESC
    ");
}

// Success messages
$msg = '';
if (isset($_GET['success'])) {
    $actions = ['added' => 'Medical record added successfully!', 'updated' => 'Medical record updated successfully!', 'deleted' => 'Medical record deleted successfully!'];
    $msg = $actions[$_GET['success']] ?? '';
}
?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>
                <div class="page-icon">&#128203;</div>
                Medical Records
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo; Medical Records
            </div>
        </div>
        <a href="create.php" class="btn btn-primary">
            &#10133; Add Medical Record
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success">&#10004; <?php echo $msg; ?></div>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <h2>&#128203; All Medical Records</h2>
            <span style="font-size:13px; opacity:0.85;">
                Total: <strong><?php echo $result->num_rows; ?></strong>
            </span>
        </div>
        <div class="card-body">

            <!-- Search Bar -->
            <form method="GET" action="index.php" class="search-bar">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by patient, diagnosis, treatment..."
                       value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-accent">Search</button>
                <?php if ($search): ?>
                    <a href="index.php" class="btn btn-secondary">Clear</a>
                <?php endif; ?>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Patient</th>
                            <th>Diagnosis</th>
                            <th>Treatment</th>
                            <th>Date Recorded</th>
                            <th>Last Updated</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><span class="id-badge"><?php echo $row['id']; ?></span></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['patient_name']); ?></strong>
                                </td>
                                <td>
                                    <span class="badge badge-danger">
                                        <?php echo htmlspecialchars($row['diagnosis']); ?>
                                    </span>
                                </td>
                                <td style="max-width:260px;">
                                    <span style="display:block; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; max-width:260px;"
                                          title="<?php echo htmlspecialchars($row['treatment']); ?>">
                                        <?php echo htmlspecialchars($row['treatment']); ?>
                                    </span>
                                </td>
                                <td class="text-muted fs-sm"><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
                                <td class="text-muted fs-sm"><?php echo date('M d, Y h:i A', strtotime($row['updated_at'])); ?></td>
                                <td style="text-align:center;">
                                    <a href="edit.php?id=<?php echo $row['id']; ?>" class="btn btn-warning btn-sm">
                                        &#9998; Edit
                                    </a>
                                    <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">
                                        &#128465; Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="table-empty">
                                    &#128203; No medical records found.
                                    <?php if ($search): ?>
                                        Try a different search term.
                                    <?php else: ?>
                                        <a href="create.php">Add the first medical record</a>.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

<?php
$conn->close();
require_once '../includes/footer.php';
?>
