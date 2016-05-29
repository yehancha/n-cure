<?php
require_once('common_requires.php');

function on_get($path_info) {
    if (is_search($path_info)) {
        search('%' . $path_info[2] . '%');
    } else if (is_single_get($path_info)) {
        get($path_info[1]);
    } else if (is_get_all($path_info)) {
        get_all();
    } else  {
        respond_bad_request('Resource not available');
    }
}

function on_post($path_info) {
    $patients = json_decode(file_get_contents('php://input'));

    if (!$patients) {
        respond_bad_request('Invalid request body');
    }

    $conn = get_conn_or_die();

    $stmt_insert = $conn->prepare('INSERT INTO `patient`(`name`, `address`, `city`, `description`, `disease`) VALUES (?, ?, ?, ?, ?)');
    $stmt_update = $conn->prepare('UPDATE `patient` SET `name`=?,`address`=?,`city`=?,`description`=?,`disease`=? WHERE `id`=?');

    $conn->begin_transaction();

    foreach ($patients as $patient) {
        $updating = isset($patient->id);

        if ($updating) {
            $stmt_update->bind_param('ssssss', $patient->name, $patient->address, $patient->city, $patient->description, $patient->disease, $patient->id);
            $error_message = execute_statement($stmt_update);
        } else {
            $stmt_insert->bind_param('sssss', $patient->name, $patient->address, $patient->city, $patient->description, $patient->disease);
            $error_message = execute_statement($stmt_insert);
        }

        if ($error_message) {
            $conn->rollback();
            $stmt_insert->close();
            $stmt_update->close();
            $conn->close();

            respond_internal_server_error($error_message);
        }
    }

    $conn->commit();

    $stmt_insert->close();
    $stmt_update->close();
    $conn->close();

    respond_ok('');
}

function is_search($path_info) {
    return count($path_info) == 3 && $path_info[1] == 'search';
}

function is_single_get($path_info) {
    return count($path_info) == 2 && $path_info[1] != '';
}

function is_get_all($path_info) {
    $count = count($path_info);
    return $count == 1 || ($count == 2 && $path_info[1] == '');
}

function search($term) {
    $conn = get_conn_or_die();

    $stmt = $conn->prepare('SELECT `id` FROM `patient` WHERE `id` LIKE ? OR `name` LIKE ? OR `address` LIKE ? OR `city` LIKE ? OR `description` LIKE ? OR `disease` LIKE ?');
    $stmt->bind_param('ssssss', $term, $term, $term, $term, $term, $term);

    $result = get_result_or_die($stmt);

    $stmt->close();
    $conn->close();

    respond_ok(json_encode($result));
}

function get($id) {
    $conn = get_conn_or_die();

    $stmt = $conn->prepare('SELECT * FROM `patient` WHERE `id`=?');
    $stmt->bind_param('s', $id);

    $result = get_result_or_die($stmt);

    $stmt->close();
    $conn->close();

    if (count($result) == 0) {
        respond_not_found('');
    } else {
        respond_ok(json_encode($result));
    }
}

function get_all() {
    $conn = get_conn_or_die();

    $stmt = $conn->prepare('SELECT * FROM `patient`');

    $result = get_result_or_die($stmt);

    $stmt->close();
    $conn->close();

    respond_ok(json_encode($result));
}

function create_patient($id, $name, $address, $city, $description, $disease, $last_updated) {
    $patient = new stdClass;
    $patient->id = $id;
    $patient->name = $name;
    $patient->address = $address;
    $patient->city = $city;
    $patient->description = $description;
    $patient->disease = $disease;
    $patient->last_updated = $last_updated;

    return $patient;
}

function get_result_or_die($stmt) {
    execute_statement_or_die($stmt);

    $stmt->bind_result($id, $name, $address, $city, $description, $disease, $last_updated);

    $result = [];

    while ($stmt->fetch()) {
        array_push($result, create_patient($id, $name, $address, $city, $description, $disease, $last_updated));
    }

    return $result;
}
