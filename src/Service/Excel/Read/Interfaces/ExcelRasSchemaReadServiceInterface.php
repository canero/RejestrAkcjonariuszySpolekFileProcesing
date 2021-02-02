<?php

namespace App\Service\Excel\Read\Interfaces;

use App\Entity\RasSchemaEntity;
use Symfony\Component\HttpFoundation\Request;

interface ExcelRasSchemaReadServiceInterface
{
    /**
     * Function read file and make array of RasSchema entity
     * @param Request $request
     * @return array|null
     */
    public function read(Request $request):?array;

    /**
     * @param string $path
     * @return array<RasSchemaEntity>|null
     */
    public function readFilePath(string $path): ?array;
}
