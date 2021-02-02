<?php

namespace App\Service\Excel\Make\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface ExcelRasShameServiceInterface
{
    /**
     * @param Request $request
     * @param ExcelRasSchemaContentInterface $rasSchemaContent
     * @param array $params
     * @return string
     */
    public function generate(Request $request, ExcelRasSchemaContentInterface $rasSchemaContent, array $params = []): string;
}
