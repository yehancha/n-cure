<?php
if ($_SERVER['REQUEST_METHOD'] == 'GET' && function_exists(on_get)) {
    on_get(get_path_info());
} else if ($_SERVER['REQUEST_METHOD'] == 'POST' && function_exists(on_post)) {
    on_post(get_path_info());
} else if ($_SERVER['REQUEST_METHOD'] == 'PUT' && function_exists(on_put)) {
    on_put(get_path_info());
}

function get_path_info() {
    return explode('/', $_SERVER['PATH_INFO']);
}
?>
