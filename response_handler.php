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

function respond_unauthorized($data) {
    respond(401, $data);
}

function respond_not_found($data) {
    respond(404, $data);
}

function respond_conflict($data) {
    respond(409, $data);
}

function respond_unsupported_media_type($data) {
    respond(415, $data);
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
