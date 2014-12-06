<?php

namespace XN\Metadata;

use Symfony\Component\HttpFoundation\File\File;

interface FileInterface
{
    public function getAbsolutePath();
    public function getWebPath();

    public function setFile(File $file = null);
    public function getFile();

    public function preUpload();
    public function upload();
    public function removeUpload();
}
