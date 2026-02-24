<?php
// ============================================
// TCH Medical Center - Appointments List
// ============================================
$base_url   = '../';
$page_title = 'Appointments';
require_once '../config/database.php';
require_once '../includes/header.php';
require_once '../includes/navbar.php';

// Handle search
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
if ($search !== '') {
    $s      = $conn->real_escape_string($search);
    $result = $conn->query("
        SELECT a.*, p.full_name AS patient_name, d.full_name AS doctor_name, d.specialization
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        JOIN doctors  d ON a.doctor_id  = d.id
        WHERE p.full_name LIKE '%$s%' OR d.full_name LIKE '%$s%' OR d.specialization LIKE '%$s%'
        ORDER BY a.appointment_date DESC
    ");
} else {
    $result = $conn->query("
        SELECT a.*, p.full_name AS patient_name, d.full_name AS doctor_name, d.specialization
        FROM appointments a
        JOIN patients p ON a.patient_id = p.id
        JOIN doctors  d ON a.doctor_id  = d.id
        ORDER BY a.appointment_date DESC
    ");
}

// Success messages
$msg = '';
if (isset($_GET['success'])) {
    $actions = ['added' => 'Appointment scheduled successfully!', 'updated' => 'Appointment updated successfully!', 'deleted' => 'Appointment deleted successfully!'];
    $msg = $actions[$_GET['success']] ?? '';
}
?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>
                <div class="page-icon">&#128197;</div>
                Appointments
            </h1>
            <div class="breadcrumb">
                <a href="../index.php">Dashboard</a> &rsaquo; Appointments
            </div>
        </div>
        <a href="create.php" class="btn btn-primary">
            &#10133; Schedule Appointment
        </a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-success">&#10004; <?php echo $msg; ?></div>
    <?php endif; ?>

    <!-- Table Card -->
    <div class="card">
        <div class="card-header">
            <h2>&#128197; All Appointments</h2>
            <span style="font-size:13px; opacity:0.85;">
                Total: <strong><?php echo $result->num_rows; ?></strong>
            </span>
        </div>
        <div class="card-body">

            <!-- Search Bar -->
            <form method="GET" action="index.php" class="search-bar">
                <input type="text" name="search" class="form-control"
                       placeholder="Search by patient, doctor, specialization..."
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
                            <th>Doctor</th>
                            <th>Specialization</th>
                            <th>Appointment Date</th>
                            <th>Scheduled On</th>
                            <th style="text-align:center;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()):
                                $appt_date = strtotime($row['appointment_date']);
                                $is_past   = $appt_date < time();
                                $is_today  = date('Y-m-d', $appt_date) === date('Y-m-d');
                            ?>
                            <tr>
                                <td><span class="id-badge"><?php echo $row['id']; ?></span></td>
                                <td><strong><?php echo htmlspecialchars($row['patient_name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['doctor_name']); ?></td>
                                <td>
                                    <span class="badge badge-info">
                                        <?php echo htmlspecialchars($row['specialization']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($is_today): ?>
                                        <span class="badge badge-success">Today</span>
                                    <?php elseif ($is_past): ?>
                                        <span class="badge badge-danger">Past</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Upcoming</span>
                                    <?php endif; ?>
                                    <span class="fs-sm text-muted" style="margin-left:4px;">
                                        <?php echo date('M d, Y h:i A', $appt_date); ?>
                                    </span>
                                </td>
                                <td class="text-muted fs-sm"><?php echo date('M d, Y h:i A', strtotime($row['created_at'])); ?></td>
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
                                    &#128197; No appointments found.
                                    <?php if ($search): ?>
                                        Try a different search term.
                                    <?php else: ?>
                                        <a href="create.php">Schedule the first appointment</a>.
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
