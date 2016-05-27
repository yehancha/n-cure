<?php
require_once 'database.php';
require_once 'method_handler.php';

function on_get($path_info) {
    $user_id = 0;

    if (count($path_info) == 3 && $path_info[1] == 'user') {
        $user_id = $path_info[2];
    }
    
    return_appointments($user_id);
}

function return_appointments($user_id) {
    echo 'appointment array for user ' . $user_id;
}

?>
