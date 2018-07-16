<?php
/**
 * Created by PhpStorm.
 * User: wilder10
 * Date: 16/07/18
 * Time: 15:57
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = uniqid() . '.' . $file->guessExtension();
        $file->move(
            $this->targetDirectory,
            $fileName
        );

        return $fileName;
    }
}