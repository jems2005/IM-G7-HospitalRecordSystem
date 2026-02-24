<?php
// ============================================
// TCH Medical Center - Navbar Include
// ============================================
$current_page = basename(dirname($_SERVER['PHP_SELF']));
$current_file = basename($_SERVER['PHP_SELF']);

function isActive($section, $current) {
    return ($current === $section) ? 'active' : '';
}
?>
<nav class="navbar">
    <!-- Brand -->
    <a href="<?php echo $base_url ?? ''; ?>index.php" class="navbar-brand">
        <div class="brand-icon">&#9877;</div>
        <div class="brand-text">
            TCH Medical Center
            <span>Hospital Record System</span>
        </div>
    </a>

    <!-- Navigation Links -->
    <div class="navbar-nav">
        <a href="<?php echo $base_url ?? ''; ?>index.php"
           class="<?php echo ($current_file === 'index.php' && $current_page === 'G-7-Hospital Record System' || $current_page === '.') ? 'active' : ''; ?>">
            <span class="nav-icon">&#127968;</span> Dashboard
        </a>
        <a href="<?php echo $base_url ?? ''; ?>patients/index.php"
           class="<?php echo isActive('patients', $current_page); ?>">
            <span class="nav-icon">&#128101;</span> Patients
        </a>
        <a href="<?php echo $base_url ?? ''; ?>doctors/index.php"
           class="<?php echo isActive('doctors', $current_page); ?>">
            <span class="nav-icon">&#129658;</span> Doctors
        </a>
        <a href="<?php echo $base_url ?? ''; ?>appointments/index.php"
           class="<?php echo isActive('appointments', $current_page); ?>">
            <span class="nav-icon">&#128197;</span> Appointments
        </a>
        <a href="<?php echo $base_url ?? ''; ?>medical_records/index.php"
           class="<?php echo isActive('medical_records', $current_page); ?>">
            <span class="nav-icon">&#128203;</span> Medical Records
        </a>
    </div>
</nav>
