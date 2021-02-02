<?php

namespace App\Service\Excel\Make\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface ExcelMakeAddEntitiesFileServiceInterface
{
    /**
     * @param Request $request
     * @return string
     */
    public function make(Request $request): string;
}
