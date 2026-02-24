<?php
// ============================================
// TCH Medical Center - Header Include
// ============================================
if (!isset($page_title)) {
    $page_title = 'TCH Medical Center';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?> | TCH Medical Center</title>
    <link rel="stylesheet" href="<?php echo $base_url ?? ''; ?>assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>⚕️</text></svg>">
</head>
<body>
