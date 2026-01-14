<?php

namespace App\Service\FileUploader;

use Symfony\Component\HttpFoundation\File\UploadedFile;

Interface FileUploaderInterface
{
    public function upload(UploadedFile $file): string;
    public function remove(String $filename): void;

}