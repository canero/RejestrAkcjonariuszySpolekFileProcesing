<?php

namespace App\Service\Excel\Make\Interfaces;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

interface ExcelRasSchemaContentInterface
{
    /**
     * @param Worksheet $workSheet
     */
    public function makeHeader(Worksheet $workSheet);

    /**
     * @param Worksheet $workSheet
     * @param array $arrayOfRasSchema
     * @param array $params
     */
    public function makeContent(Worksheet $workSheet, array $arrayOfRasSchema, array $params = []);
}
