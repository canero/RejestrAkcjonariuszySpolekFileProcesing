<?php

namespace App\Service\Excel\Download\Interfaces;

use Symfony\Component\HttpFoundation\Response;

interface ExcelDownloadServiceInterface
{
    /**
     * function check if file exist nest download file
     * @param string $name
     * @return Response
     */
    public function download(string $name): Response;
}
