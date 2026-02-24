<?php
// ============================================
// TCH Medical Center - Doctors List
// ============================================
$base_url   = '../';
$page_title = 'Doctors';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $s      = $conn->real_escape_string($search);
    $result = $conn->query("SELECT * FROM doctors WHERE full_name LIKE '%$s%' OR specialization LIKE '%$s%' OR contact_number LIKE '%$s%' ORDER BY created_at DESC");
} else {
    $result = $conn->query("SELECT * FROM doctors ORDER BY created_at DESC");
}

// Success messages
$msg = '';
if (isset($_GET['success'])) {
    $actions = ['added' => 'Doctor added successfully!', 'updated' => 'Doctor updated successfully!', 'deleted' => 'Doctor deleted successfully!'];
    $msg = $actions[$_GET['success']] ?? '';
}
?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>
                <div class="page-icon">&#129658;</div>
                Doctors
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo; Doctors
            </div>
        </div>
        <a href="create.php" class="btn btn-primary">
            &#10133; Add New Doctor
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success">&#10004; <?php echo $msg; ?></div>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <h2>&#129658; All Doctors</h2>
            <span style="font-size:13px; opacity:0.85;">
                Total: <strong><?php echo $result->num_rows; ?></strong>
            </span>
        </div>
        <div class="card-body">

            <!-- Search Bar -->
            <form method="GET" action="index.php" class="search-bar">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by name, specialization..."
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
                            <th>Specialization</th>
                            <th>Contact Number</th>
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
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo htmlspecialchars($row['specialization']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="font-family: monospace; font-size:13px;">
                                        <?php echo htmlspecialchars($row['contact_number']); ?>
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
                                    &#129658; No doctors found.
                                    <?php if ($search): ?>
                                        Try a different search term.
                                    <?php else: ?>
                                        <a href="create.php">Add the first doctor</a>.
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
