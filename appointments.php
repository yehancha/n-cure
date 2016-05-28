<?php
require_once('common_requires.php');

function on_get($path_info) {
    $user_id = 0;

    if (is_valid_user_path($path_info)) {
        $user_id = $path_info[2];
    }

    $conn = get_conn_or_die();

    if ($user_id != 0) {
        $stmt = $conn->prepare('SELECT * FROM `appointment` WHERE `user_id`=?');
        $stmt->bind_param('s', $user_id);
    } else {
        $stmt = $conn->prepare('SELECT * FROM `appointment`');
    }

    execute_statement_or_die($stmt);

    $stmt->bind_result($id, $user_id, $time, $description);

    $response = [];

    while ($stmt->fetch()) {
        array_push($response, create_appointment($id, $user_id, $time, $description));
    }

    $stmt->close();
    $conn->close();

    respond_ok(json_encode($response));
}

function on_post($path_info) {
    if (!is_valid_user_path($path_info) || $path_info[2] == '') {
        respond_bad_request('Missing params');
    }

    $user_id = $path_info[2];
    $id = $_POST['id']; // for updating
    $time = $_POST['time'];
    $description = $_POST['description'];

    if (!isset($time) || !isset($description)) {
        respond_bad_request('Missing params');
    }

    $conn = get_conn_or_die();

    $updating = isset($id);

    if ($updating) {
        $stmt = get_update_statement_or_die($conn, $id, $time, $description);
    } else {
        $stmt = $conn->prepare('INSERT INTO `appointment` (user_id, time, description) VALUES (?, ?, ?)');
        $stmt->bind_param('sss', $user_id, $time, $description);
    }

    execute_statement_or_die($stmt);

    $insert_id = $stmt->insert_id;

    $stmt->close();
    $conn->close();

    if ($updating) {
        respond_ok(json_encode(get_appointment($id)));
    } else {
        respond_created(json_encode(get_appointment($insert_id)));
    }
}

function is_valid_user_path($path_info) {
    return count($path_info) == 3 && $path_info[1] == 'user';
}

function get_conn_or_die() {
    $conn = get_mysqli_conn();
    if ($conn->connect_error) {
        respond_internal_server_error($conn->connection_error);
    }

    return $conn;
}

function get_update_statement_or_die($conn, $id, $time, $description) {
    $stmt = $conn->prepare('SELECT 1 FROM `appointment` WHERE `id`=?');
    $stmt->bind_param('s', $id);

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->error) {
        // let's extract only the message without structural data
        $message = explode('(', $stmt->error)[0];
        respond_internal_server_error($message);
    }

    if ($stmt->num_rows == 0) {
        respond_bad_request('Cannot find appointment specified');
    }

    $stmt->close();

    $stmt = $conn->prepare('UPDATE `appointment` SET `time`=?, `description`=? WHERE `id`=?');
    $stmt->bind_param('sss', $time, $description, $id);

    return $stmt;
}

function execute_statement_or_die($stmt) {
    $stmt->execute();
    $error = $stmt->error;

    if ($error) {
        // let's extract only the message without structural data
        $message = explode('(', $error)[0];
        respond_internal_server_error($message);
    }
}

function create_appointment($id, $user_id, $time, $description) {
    $appointment = new stdClass;
    $appointment->id = $id;
    $appointment->user_id = $user_id;
    $appointment->time = $time;
    $appointment->description = $description;

    return $appointment;
}

function get_appointment($id) {
    $conn = get_conn_or_die();

    $stmt = $conn->prepare('SELECT * FROM `appointment` WHERE `id`=?');
    $stmt->bind_param('s', $id);

    execute_statement_or_die($stmt);

    $stmt->bind_result($id, $user_id, $time, $description);
    $stmt->fetch();

    $appointment = create_appointment($id, $user_id, $time, $description);

    $stmt->close();
    $conn->close();

    return $appointment;
}
?>
