<?php

namespace App\Controller\Excel;

use App\Service\Excel\Download\ExcelDownloadService;
use App\Service\Excel\Download\Interfaces\ExcelDownloadServiceInterface;
use App\Utils\HttpService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DownloadFileController extends AbstractController
{
    /**
     * @Route("/api/excel/download/file/{fileName}", name="excel_download_file")
     * @param string $fileName
     * @param ExcelDownloadServiceInterface $excelDownloadService
     * @return Response
     */
    public function index(string $fileName, ExcelDownloadServiceInterface $excelDownloadService): Response
    {
        return $excelDownloadService->download($fileName);
    }
}
