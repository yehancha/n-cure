<?php
function get_mysqli_conn() {
    return new mysqli('localhost', 'root', 'root', 'n_cure');
}

function get_conn_or_die() {
    $conn = get_mysqli_conn();
    if ($conn->connect_error) {
        respond_internal_server_error($conn->connection_error);
    }

    return $conn;
}

function execute_statement_or_die($stmt) {
    $error_message = execute_statement($stmt);

    if ($error_message) {
        respond_internal_server_error($error_message);
    }
}

function execute_statement($stmt) {
    $stmt->execute();
    $error = $stmt->error;

    // let's extract only the message without structural data, if there is an error
    // otherwise a simple null
    return $error ? explode('(', $error)[0] : null;
}
?>
