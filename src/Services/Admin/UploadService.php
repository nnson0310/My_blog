<?php

namespace App\Services\Admin;

use App\ServiceInterfaces\Admin\UploadServiceInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadService implements UploadServiceInterface
{

    private $slugger;

    private $targetDirectory;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
        $this->targetDirectory = $targetDirectory;
    }

    public function uploadFile(UploadedFile $file)
    {
        $originalFileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFileName = $this->slugger->slug($originalFileName);
        $fileName = $safeFileName . '.' . uniqid() . '.' . $file->guessExtension();

        try {
            $file->move($this->getTargetDirectory().'/', $fileName);
            return $fileName;
        } catch (FileException $e) {
            return false;
        }
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
