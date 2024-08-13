<?php
session_start();
header('Content-Type: application/json');
echo json_encode([
    'checkedCheckboxes' => isset($_SESSION['checkedCheckboxes']) ? $_SESSION['checkedCheckboxes'] : []
]);
?>
