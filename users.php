<?php
require_once('common_requires.php');

function on_get($path_info) {
    if (count($path_info) < 2) {
        respond_bad_request('Missing params');
    }

    $conn = get_conn_or_die();
    $stmt = $conn->prepare('SELECT 1 FROM `user` WHERE `id`=?');
    $stmt->bind_param('s', $path_info[1]);

    $stmt->execute();
    $stmt->store_result();

    if ($stmt->error) {
        // let's extract only the message without structural data
        $message = explode('(', $stmt->error)[0];
        respond_internal_server_error($message);
    }

    if ($stmt->num_rows > 0) {
        respond_ok('');
    } else {
        respond_unauthorized('Wrong user id');
    }
}

?>
