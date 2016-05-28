<?php
require_once('common_requires.php');

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
        respond_internal_server_error($conn->connection_error);
    }

    if ($user_id != 0) {
        $stmt = $conn->prepare('SELECT * FROM `appointment` WHERE `user_id`=?');
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

    $stmt->close();
    $conn->close();

    respond_ok(json_encode($response));
}

function on_post($path_info) {
    if (count($path_info) != 3 || $path_info[1] != 'user' || $path_info[2] == '') {
        respond_bad_request('Missing params');
    }

    $user_id = $path_info[2];
    $time = $_POST['time'];
    $description = $_POST['description'];

    if (!isset($time) || !isset($description)) {
        respond_bad_request('Missing params');
    }

    $conn = get_mysqli_conn();
    if ($conn->connect_error) {
        respond_internal_server_error($conn->connection_error);
    }

    $stmt = $conn->prepare('INSERT INTO `appointment` (user_id, time, description) VALUES (?, ?, ?)');
    $stmt->bind_param('sss', $user_id, $time, $description);

    $stmt->execute();
    $error = $stmt->error;

    $stmt->close();
    $conn->close();

    if ($error) {
        // let's extract only the message without structural data
        $message = explode('(', $error)[0];
        respond_internal_server_error($message);
    } else {
        respond_created('');
    }
}
?>
