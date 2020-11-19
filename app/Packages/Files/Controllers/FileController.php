<?php

namespace App\Packages\Files\Controllers;

use App\Packages\Files\Repositories\FileRepository;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;

class FileController
{
    private FileRepository $fileRepository;

    public function __construct(FileRepository $fileRepository)
    {
        $this->fileRepository = $fileRepository;
    }

    public function getByOriginalName(Request $request): BinaryFileResponse
    {
        $file_name = $request->get('file_name', null);

        if (!$file_name) {
            return $this->getPlaceholder();
        }

        $file = $this->fileRepository->getByField('original_name', $file_name);

        if (!$file) {
            return $this->getPlaceholder();
        }

        if(!file_exists($file['file_path'])){
            return $this->getPlaceholder();
        }

        $response = (new BinaryFileResponse($file['file_path']));

        $response->headers->set('Content-Type', $file['mimetype']);

        return $response->send();
    }

    public function getPlaceholder(): BinaryFileResponse
    {
        $placeholder = storage_path('defaults/placeholder.jpg');
        $response = (new BinaryFileResponse($placeholder));

        $response->headers->set('Content-Type', 'image/jpeg');

        return $response->send();
    }
}
