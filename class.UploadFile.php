<?php
/*
 * Copyright 2014, Ekin K. <dual@openmailbox.org>
 *
 * Documentation:
 * https://github.com/iamdual/php-upload-file
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class UploadFile {

    private $error_messages = array(
        "empty_file" => "File is not selected.",
        "invalid_ext" => "File extension is invalid.",
        "invalid_type" => "File type is invalid.",
        "long_size" => "File size is too long.",
        "file_exists" => "File is already exists.",
        "unknown_error" => "File is not uploaded the server.",
    );

    private $error = null;
    private $file = null;
    private $extensions = null;
    private $types = null;
    private $max_size = null;
    private $path = null;
    private $new_name = null;
    private $override = false;

    function __construct($file) {

        $this->file = $file;

    }

    public function allowed_extensions($extensions) {

        $this->extensions = (is_array($extensions) ? $extensions : null);

    }

    public function allowed_types($types) {

        $this->types = (is_array($types) ? $types : null);

    }

    public function max_size($size) {

        $this->max_size = (is_numeric($size) ? $size : null);

    }

    public function override($bool) {

        $this->override = ($bool === true ? true : false);

    }

    public function path($path) {

        $this->path = $path;

    }

    public function new_name($name) {

        $this->new_name = $name . "." . $this->get_ext($this->file["name"]);

    }

    public function get_name() {

        if ($this->new_name === null) {

            return $this->file["name"];

        }
        else {

            return $this->new_name;

        }

    }

    public function check() {

        if (!isset($this->file["name"]) || !isset($this->file["tmp_name"]) || !isset($this->file["type"]) || !isset($this->file["size"]) || !isset($this->file["error"])) {
            $this->error = $this->error_messages["empty_file"];
        }
        else if (strlen($this->file["name"]) == 0 || strlen($this->file["tmp_name"]) == 0 || strlen($this->file["type"]) == 0) {
            $this->error = $this->error_messages["empty_file"];
        }
        else if ($this->extensions !== null && !in_array($this->get_ext($this->file["name"]), $this->extensions)) {
            $this->error = $this->error_messages["invalid_ext"];
        }
        else if ($this->types !== null && !in_array($this->file["type"], $this->types)) {
            $this->error = $this->error_messages["invalid_type"];
        }
        else if ($this->max_size !== null && $this->file["size"] > $this->mb_to_byte($this->max_size)) {
            $this->error = $this->error_messages["long_size"];
        }
        else if ($this->override === false && file_exists($this->get_path($this->get_name()))) {
            $this->error = $this->error_messages["file_exists"];
        }
        else if ($this->file["error"] == 1 && $this->file["error"] == 2) {
            $this->error = $this->error_messages["long_size"];
        }
        else if ($this->file["error"] == 4) {
            $this->error = $this->error_messages["empty_file"];
        }
        else if ($this->file["error"] > 0) {
            $this->error = $this->error_messages["unknown_error"];
        }

        if ($this->error === null) {
            return true;
        }
        else {
            return false;
        }

    }

    public function error() {

        return $this->error;

    }

    public function upload() {

        if ($this->check()) {

            if (!file_exists($this->get_path())) {
                mkdir($this->get_path(), 0777, true);
            }

            @move_uploaded_file($this->file["tmp_name"], $this->get_path($this->get_name()));

        }

    }

    public function get_path($filename = null) {

        $path = null;

        if ($this->path !== null) {
            $path = rtrim($this->path, "/") . "/";
        }

        if ($filename !== null) {
            $filename = rtrim($filename, "/");
        }

        return $path . $filename;

    }

    private function get_ext($filename) {

        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));

    }

    private function mb_to_byte($filesize) {

        return $filesize * 1000000;

    }

}