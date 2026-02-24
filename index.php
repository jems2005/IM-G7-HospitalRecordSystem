<?php
// ============================================
// TCH Medical Center - Dashboard
// ============================================
$base_url   = '';
$page_title = 'Dashboard';
require_once 'config/database.php';
require_once 'includes/header.php';
require_once 'includes/navbar.php';

// --- Fetch counts ---
$total_patients    = $conn->query("SELECT COUNT(*) AS cnt FROM patients")->fetch_assoc()['cnt'];
$total_doctors     = $conn->query("SELECT COUNT(*) AS cnt FROM doctors")->fetch_assoc()['cnt'];
$total_appointments = $conn->query("SELECT COUNT(*) AS cnt FROM appointments")->fetch_assoc()['cnt'];
$total_records     = $conn->query("SELECT COUNT(*) AS cnt FROM medical_records")->fetch_assoc()['cnt'];

// --- Recent Patients (last 5) ---
$recent_patients = $conn->query("SELECT * FROM patients ORDER BY created_at DESC LIMIT 5");

// --- Upcoming Appointments (next 5) ---
$upcoming_appts = $conn->query("
    SELECT a.*, p.full_name AS patient_name, d.full_name AS doctor_name, d.specialization
    FROM appointments a
    JOIN patients p ON a.patient_id = p.id
    JOIN doctors d ON a.doctor_id = d.id
    ORDER BY a.appointment_date ASC
    LIMIT 5
");
?>

<div class="main-content">

    <!-- Page Header -->
    <div class="page-header">
        <div>
            <h1>
                <div class="page-icon">&#127968;</div>
                Dashboard
            </h1>
            <div class="breadcrumb">Welcome to TCH Medical Center &mdash; Hospital Record System</div>
        </div>
        <div style="font-size:13px; color:var(--text-muted);">
            &#128197; <?php echo date('l, F j, Y'); ?>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card patients">
            <div class="stat-icon">&#128101;</div>
            <div class="stat-info">
                <h3><?php echo $total_patients; ?></h3>
                <p>Total Patients</p>
            </div>
        </div>
        <div class="stat-card doctors">
            <div class="stat-icon">&#129658;</div>
            <div class="stat-info">
                <h3><?php echo $total_doctors; ?></h3>
                <p>Total Doctors</p>
            </div>
        </div>
        <div class="stat-card appts">
            <div class="stat-icon">&#128197;</div>
            <div class="stat-info">
                <h3><?php echo $total_appointments; ?></h3>
                <p>Appointments</p>
            </div>
        </div>
        <div class="stat-card records">
            <div class="stat-icon">&#128203;</div>
            <div class="stat-info">
                <h3><?php echo $total_records; ?></h3>
                <p>Medical Records</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card mb-3">
        <div class="card-header">
            <h2>&#9889; Quick Actions</h2>
        </div>
        <div class="card-body">
            <div class="quick-actions">
                <a href="patients/create.php" class="quick-action-card">
                    <span class="qa-icon">&#10133;</span>
                    <h4>Add Patient</h4>
                    <p>Register a new patient</p>
                </a>
                <a href="doctors/create.php" class="quick-action-card">
                    <span class="qa-icon">&#129658;</span>
                    <h4>Add Doctor</h4>
                    <p>Register a new doctor</p>
                </a>
                <a href="appointments/create.php" class="quick-action-card">
                    <span class="qa-icon">&#128197;</span>
                    <h4>Schedule Appointment</h4>
                    <p>Book a new appointment</p>
                </a>
                <a href="medical_records/create.php" class="quick-action-card">
                    <span class="qa-icon">&#128203;</span>
                    <h4>Add Medical Record</h4>
                    <p>Create a new medical record</p>
                </a>
            </div>
        </div>
    </div>

    <!-- Dashboard Grid: Recent Patients + Upcoming Appointments -->
    <div class="dashboard-grid">

        <!-- Recent Patients -->
        <div class="card">
            <div class="card-header">
                <h2>&#128101; Recent Patients</h2>
                <a href="patients/index.php" class="btn btn-accent btn-sm">View All</a>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Gender</th>
                                <th>Registered</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($recent_patients->num_rows > 0): ?>
                                <?php while ($p = $recent_patients->fetch_assoc()): ?>
                                <tr>
                                    <td><span class="id-badge"><?php echo $p['id']; ?></span></td>
                                    <td><strong><?php echo htmlspecialchars($p['full_name']); ?></strong></td>
                                    <td>
                                        <?php
                                        $g = $p['gender'];
                                        $cls = strtolower($g) === 'male' ? 'badge-male' : (strtolower($g) === 'female' ? 'badge-female' : 'badge-other');
                                        ?>
                                        <span class="badge <?php echo $cls; ?>"><?php echo $g; ?></span>
                                    </td>
                                    <td class="text-muted fs-sm"><?php echo date('M d, Y', strtotime($p['created_at'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="table-empty">No patients found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Upcoming Appointments -->
        <div class="card">
            <div class="card-header">
                <h2>&#128197; Appointments</h2>
                <a href="appointments/index.php" class="btn btn-accent btn-sm">View All</a>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Patient</th>
                                <th>Doctor</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($upcoming_appts->num_rows > 0): ?>
                                <?php while ($a = $upcoming_appts->fetch_assoc()): ?>
                                <tr>
                                    <td><span class="id-badge"><?php echo $a['id']; ?></span></td>
                                    <td><strong><?php echo htmlspecialchars($a['patient_name']); ?></strong></td>
                                    <td class="text-muted fs-sm"><?php echo htmlspecialchars($a['doctor_name']); ?></td>
                                    <td class="text-muted fs-sm"><?php echo date('M d, Y', strtotime($a['appointment_date'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="table-empty">No appointments found.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div><!-- /.dashboard-grid -->

</div><!-- /.main-content (closed by footer) -->

<?php
$conn->close();
require_once 'includes/footer.php';
?>
