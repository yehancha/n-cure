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
    $stmt->execute();
    $error = $stmt->error;

    if ($error) {
        // let's extract only the message without structural data
        $message = explode('(', $error)[0];
        respond_internal_server_error($message);
    }
}
?>
