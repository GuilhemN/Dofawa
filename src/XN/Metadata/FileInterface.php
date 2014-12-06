<?php

namespace XN\Metadata;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileInterface
{
    public function getAbsolutePath();
    public function getWebPath();

    public function setFile(UploadedFile $file = null);
    public function getFile();

    public function preUpload();
    public function upload();
    public function removeUpload();
}
