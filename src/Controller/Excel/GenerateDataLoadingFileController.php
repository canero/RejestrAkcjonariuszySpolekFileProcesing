<?php

namespace App\Controller\Excel;

use App\Service\Excel\Make\Interfaces\ExcelMakeAddEntitiesFileServiceInterface;
use App\Utils\HttpService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenerateDataLoadingFileController extends AbstractController
{
    /**
     * @Route("/api/excel/generate/data/loading/file", name="excel_generate_data_loading_file", methods={"POST"})
     * @param HttpService $httpService
     * @param Request $request
     * @param ExcelMakeAddEntitiesFileServiceInterface $excelMakeAddEntitiesFileService
     * @return Response
     */
    public function index(HttpService $httpService, Request $request, ExcelMakeAddEntitiesFileServiceInterface $excelMakeAddEntitiesFileService): Response
    {
        return $httpService->responseJSON($excelMakeAddEntitiesFileService->make($request));
    }
}
