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

    $stmt = $conn->prepare('SELECT * FROM `patient` WHERE `id` LIKE ? OR `name` LIKE ? OR `address` LIKE ? OR `city` LIKE ? OR `description` LIKE ? OR `disease` LIKE ?');
    $stmt->bind_param('ssssss', $term, $term, $term, $term, $term, $term);

    execute_statement_or_die($stmt);

    $stmt->bind_result($id, $name, $address, $city, $description, $disease, $last_updated);

    $response = [];

    while ($stmt->fetch()) {
        array_push($response, create_patient($id, $name, $address, $city, $description, $disease, $last_updated));
    }

    $stmt->close();
    $conn->close();

    respond_ok(json_encode($response));
}

function get($id) {
    echo 'Getting...';
}

function get_all() {
    echo 'Getting all...';
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
