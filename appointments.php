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
    $conn = get_mysqli_conn();
    if ($conn->connect_error) {
        echo $conn->connection_error;
        return;
    }

    if ($user_id != 0) {
        $stmt = $conn->prepare("SELECT * FROM `appointment` WHERE `user_id`=?");
        $stmt->bind_param('s', $user_id);
    } else {
        $stmt = $conn->prepare('SELECT * FROM `appointment`');
    }

    $stmt->execute();
    $stmt->bind_result($id, $user_id, $time, $description);

    $response = [];

    while ($stmt->fetch()) {
        $appointment = new stdClass;
        $appointment->id = $id;
        $appointment->user_id = $user_id;
        $appointment->time = $time;
        $appointment->description = $description;

        array_push($response, $appointment);
    }

    echo json_encode($response);
}
?>
