<?php
class FileUploader {
    private $targetDir;
    private $maxFileSize;
    private $allowedFileTypes;

    public function __construct($targetDir = "uploads/", $maxFileSize = 500000, $allowedFileTypes = ['jpg', 'png', 'jpeg', 'gif']) {
        $this->targetDir = $targetDir;
        $this->maxFileSize = $maxFileSize;
        $this->allowedFileTypes = $allowedFileTypes;
    }

    public function upload($file) {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return "Error uploading file!";
        }

        $targetFile = $this->targetDir . basename($file['name']);
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        if (getimagesize($file['tmp_name']) === false) {
            return "File is not an image.";
        }

        if ($file['size'] > $this->maxFileSize) {
            return "File is too large.";
        }

        if (!in_array($imageFileType, $this->allowedFileTypes)) {
            return "Only JPG, JPEG, PNG & GIF files are allowed.";
        }

        if (!move_uploaded_file($file['tmp_name'], $targetFile)) {
            return "Error in uploading file.";
        }

        return $targetFile;  // success
    }
}
