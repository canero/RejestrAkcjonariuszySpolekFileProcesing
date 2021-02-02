<?php

namespace App\Service\Excel\Read\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface ExcelReadFileServiceInterface
{
    /**
     * Function read file and make array of RasSchema entity
     * @param Request $request
     * @return array|null
     */
    public function read(Request $request): ?array;

    /**
     * @param string $path
     * @return array|null
     */
    public function readPath(string $path): ?array;
}
