php-Upload-File
===============

Safe, useful, and simple PHP file upload class.

#### Using

```php
require("class.UploadFile.php");

if (isset($_FILES["file"])) {

    $upload = new UploadFile($_FILES["file"]);
    $upload->allowed_extensions(array("png", "jpg", "jpeg", "gif"));
    $upload->allowed_types(array("image/png", "image/jpeg"));
    $upload->max_size(5); //Mb
    $upload->new_name("hello");
    $upload->path("upload/files");
    $upload->override(true);

    if (!$upload->check()) {
        echo "Upload error: " . $upload->error();
    }
    else {
        $upload->upload();
        echo "Upload successful!";
    }

}
```

#### Methods

| Name & Type | Description | 
| ----------- | ----------- |
| `allowed_extensions(array())` | Allowed file extensions. Default is all. |
| `allowed_types(array())` | Allowed mime types. Default is all. |
| `max_size(int)` | Max file size (megabyte). Default is unlimited.  |
| `new_name(String)` | Rename file. Default is current. |
| `path(String)` | Upload files directory. Default is script path. |
| `override(boolean)` | Force upload even if file exists. Default is getting error. |
| `check()` | Is successfully. Return: TRUE or FALSE |
| `error()` | Get error text. Return: String |
| `upload()` |  Upload file. |
| `get_name()` |  Get uploaded file name. Return: String |
| `get_path(String)` |  Get with path. Return: String |


#### Getting file name with path
```php
echo $upload->get_path($upload->get_name()); // uploads/files/hello.png
```
