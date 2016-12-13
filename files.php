<?php
require_once('response_handler.php');

$target_dir = 'uploads/';
$target_file = $target_dir . basename($_FILES['fileToUpload']['name']);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST['submit'])) {
    $check = getimagesize($_FILES['fileToUpload']['tmp_name']);
    if($check !== false) {
        // File is an image
        $uploadOk = 1;
    } else {
        respond_unsupported_media_type('File is not an image.');
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    respond_conflict('Sorry, file already exists.');
    $uploadOk = 0;
}
// Check file size
// if ($_FILES["fileToUpload"]["size"] > 500000) {
//     echo "Sorry, your file is too large.";
//     $uploadOk = 0;
// }
// Allow certain file formats
if($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif' ) {
    respond_unsupported_media_type('Sorry, only JPG, JPEG, PNG & GIF files are allowed.');
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    respond_bad_request('Sorry, your file was not uploaded.');
// if everything is ok, try to upload file
} else {
    // if (!file_exists($target_dir)) {
    //     mkdir($target_dir, 0777, true);
    // }

    if (move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $target_file)) {
        respond_ok('');
    } else {
        respond_internal_server_error('Sorry, there was an error uploading your file.');
    }
}
?>
