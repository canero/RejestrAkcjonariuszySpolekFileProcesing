<?php

namespace App\Controller\Excel;

use App\Service\Excel\Make\ExcelDataLoadingFileService;
use App\Service\Excel\Make\Interfaces\ExcelRasShameServiceInterface;
use App\Utils\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadRasSchemaController extends AbstractController
{
    /**
     * @Route("/api/excel/upload/ras/schema", name="excel_upload_ras_schema")
     * @param Request $request
     * @param HttpService $httpService
     * @param ExcelRasShameServiceInterface $excelDataLoadingFileService
     * @param ExcelDataLoadingFileService $dataLoadingFileService
     * @return Response
     */
    public function index(Request $request, HttpService $httpService, ExcelRasShameServiceInterface $excelDataLoadingFileService, ExcelDataLoadingFileService $dataLoadingFileService): Response
    {
        return  $httpService->responseJSON($excelDataLoadingFileService->generate($request,  $dataLoadingFileService));
    }
}
