<?php

require("class.UploadFile.php");

if (isset($_FILES["file"])) {

    $upload = new UploadFile($_FILES["file"]);
    $upload->allowed_extensions(array("png", "jpg", "jpeg", "gif"));
    $upload->allowed_types(array("image/png", "image/jpeg"));
    $upload->max_size(5);
    $upload->path("upload/files/asd");
    $upload->override(true);

    if (!$upload->check()) {
        echo "Upload error: " . $upload->error();
    }
    else {
        $upload->upload();
        echo "Upload successful!";
    }

}
?>

<form enctype="multipart/form-data" action="" method="post">
    Select File: <input type="file" name="file"> <input type="submit" value="Upload">
</form>