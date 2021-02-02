<?php

namespace App\Service\Excel\Make\Interfaces;

use Symfony\Component\HttpFoundation\Request;

interface ExcelDataLoadingFileServiceInterface
{
    public function generate(Request $request, bool $withNumber = false);
}
