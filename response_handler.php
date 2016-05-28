<?php
function respond_ok($data) {
    respond(200, $data);
}

function respond_created($data) {
    respond(201, $data);
}

function respond_bad_request($data) {
    respond(400, $data);
}

function respond_not_found($data) {
    respond(404, $data);
}

function respond_internal_server_error($data) {
    respond(500, $data);
}

function respond($code, $data) {
    http_response_code($code);
    echo $data;
    die();
}
?>
