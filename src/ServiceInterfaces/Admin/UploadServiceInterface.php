<?php

namespace App\ServiceInterfaces\Admin;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadServiceInterface
{
    public function uploadFile(UploadedFile $file);
}