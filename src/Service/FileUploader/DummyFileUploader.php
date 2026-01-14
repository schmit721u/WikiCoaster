<?php
namespace App\Service\FileUploader;

use App\Service\FileUploader\FileUploaderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class DummyFileUploader implements FileUploaderInterface
{
    public function upload(UploadedFile $file): string
    {
        return dump(sprintf("Dummy file %s uploaded", $file->getBasename()));
    }
    public function remove(string $filename): void
    {
        dump("Dummy file removed");
    }
}