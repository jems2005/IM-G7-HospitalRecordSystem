<?php
// ============================================
// TCH Medical Center - Patients List
// ============================================
$base_url   = '../';
$page_title = 'Patients';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $s = $conn->real_escape_string($search);
    $result = $conn->query("SELECT * FROM patients WHERE full_name LIKE '%$s%' OR gender LIKE '%$s%' ORDER BY created_at DESC");
} else {
    $result = $conn->query("SELECT * FROM patients ORDER BY created_at DESC");
}

// Success/error messages
$msg = '';
if (isset($_GET['success'])) {
    $actions = ['added' => 'Patient added successfully!', 'updated' => 'Patient updated successfully!', 'deleted' => 'Patient deleted successfully!'];
    $msg = $actions[$_GET['success']] ?? '';
}
?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>
                <div class="page-icon">&#128101;</div>
                Patients
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo; Patients
            </div>
        </div>
        <a href="create.php" class="btn btn-primary">
            &#10133; Add New Patient
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success">&#10004; <?php echo $msg; ?></div>
    <?php endif; ?>

    <!-- Search + Table Card -->
    <div class="card">
        <div class="card-header">
            <h2>&#128101; All Patients</h2>
            <span style="font-size:13px; opacity:0.85;">
                Total: <strong><?php echo $result->num_rows; ?></strong>
            </span>
        </div>
        <div class="card-body">

            <!-- Search Bar -->
            <form method="GET" action="index.php" class="search-bar">
                <input type="text" name="search" class="form-control"
                       placeholder="&#128269; Search by name or gender..."
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
                            <th>Full Name</th>
                            <th>Date of Birth</th>
                            <th>Gender</th>
                            <th>Registered On</th>
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
                                    <strong><?php echo htmlspecialchars($row['full_name']); ?></strong>
                                </td>
                                <td><?php echo date('F j, Y', strtotime($row['date_of_birth'])); ?></td>
                                <td>
                                    <?php
                                    $g   = $row['gender'];
                                    $cls = strtolower($g) === 'male' ? 'badge-male' : (strtolower($g) === 'female' ? 'badge-female' : 'badge-other');
                                    ?>
                                    <span class="badge <?php echo $cls; ?>"><?php echo $g; ?></span>
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
                                    &#128101; No patients found.
                                    <?php if ($search): ?>
                                        Try a different search term.
                                    <?php else: ?>
                                        <a href="create.php">Add the first patient</a>.
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div><!-- /.table-responsive -->

        </div><!-- /.card-body -->
    </div><!-- /.card -->

</div>

<?php
$conn->close();
require_once '../includes/footer.php';
?>
