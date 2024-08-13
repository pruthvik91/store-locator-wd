<?php
session_start();
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['checkedCheckboxes'])) {
    // Retrieve existing states from session
    $existingStates = isset($_SESSION['checkedCheckboxes']) ? $_SESSION['checkedCheckboxes'] : [];

    // Merge new states with existing ones
    $newStates = array_unique(array_merge($existingStates, $data['checkedCheckboxes']));

    $_SESSION['checkedCheckboxes'] = $newStates;
}
