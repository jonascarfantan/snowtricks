<?php

namespace App\_Core\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private string $targetDirectory;
    private SluggerInterface $slugger;
    
    public function __construct($target_directory, SluggerInterface $slugger)
    {
        $this->target_directory = $target_directory;
        $this->slugger = $slugger;
    }
    
    public function upload(UploadedFile $file): array
    {
        $original_filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safe_filename = $this->slugger->slug($original_filename);
        $file_path = $safe_filename.'-'.uniqid().'.'.$file->guessExtension();
        $file_name = $safe_filename.'-'.uniqid();
    
        try {
            $file->move($this->getTargetDirectory(), $file_path);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }
        
        return ['file_path' => $file_path, 'file_name' => $file_name];
    }
    
    public function getTargetDirectory(): string
    {
        return $this->target_directory;
    }
}
