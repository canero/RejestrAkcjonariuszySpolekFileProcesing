<?php

namespace App\Service\File\Upload\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface FileUploadServiceInterface
{
    /**
     * @param Request $request
     * @return string
     */
    public function upload(Request $request): string;
}
