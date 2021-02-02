<?php

namespace App\Controller\File;

use App\Service\File\Upload\Interfaces\FileUploadServiceInterface;
use App\Utils\HttpService;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadFileController extends AbstractController
{
    /**
     * @Route("/api/upload/file", name="file_upload_file")
     * @param Request $request
     * @param HttpService $httpService
     * @param FileUploadServiceInterface $fileUploadService
     * @return Response
     */
    public function index(Request $request, HttpService $httpService, FileUploadServiceInterface $fileUploadService): Response
    {
        return $httpService->responseJSON($fileUploadService->upload($request));
    }
}
