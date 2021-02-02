<?php

namespace App\Controller\Excel;

use App\Service\Excel\Check\CheckExcelRasSchemaService;
use App\Service\Excel\Make\Interfaces\ExcelRasShameServiceInterface;
use App\Utils\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CheckRasSchemaController extends AbstractController
{
    /**
     * @Route("/api/excel/check/ras/schema", name="excel_check_ras_schema")
     * @param Request $request
     * @param HttpService $httpService
     * @param CheckExcelRasSchemaService $checkExcelRasSchemaService
     * @param ExcelRasShameServiceInterface $excelRasShameService
     * @return Response
     */
    public function index(Request $request, HttpService $httpService, CheckExcelRasSchemaService $checkExcelRasSchemaService, ExcelRasShameServiceInterface $excelRasShameService): Response
    {
        return $httpService->responseJSON( $excelRasShameService->generate($request,$checkExcelRasSchemaService));
    }
}
