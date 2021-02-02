<?php

namespace App\Service\Excel\Read\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface ExcelDataLoadingFileReadServiceInterface
{
    /**
     * Function read file and make array of RasSchema entity
     * @param Request $request
     * @return array<DataLoadingFileEntity>|null
     */
    public function read(Request $request): ?array;

    /**
     * @param string $path
     * @return array<DataLoadingFileEntity>|null
     */
    public function readFilePath(string $path): ?array;
}
