<?php


namespace App\Service\Excel\Download;


use App\Service\Excel\Download\Interfaces\ExcelDownloadServiceInterface;
use App\Utils\HttpService;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

class ExcelDownloadService implements ExcelDownloadServiceInterface
{

    private  HttpService $httpService;
    private string $dir;

    /**
     * ExcelDownloadService constructor.
     * @param HttpService $httpService
     * @param KernelInterface $appKernel
     */
    public function __construct(HttpService $httpService, KernelInterface $appKernel)
    {
        $this->httpService = $httpService;
        $this->dir = $appKernel->getProjectDir();
    }


    /**
     * function check if file exist nest download file
     * @param string $name
     * @return Response
     */
    public function download(string $name): Response
    {

       if(!file_exists ($this->dir . '/assets/files/' . $name ) ){
           return $this->httpService->responseJSON(json_encode(array('error' => true, 'message' => 'Błąd systemu', 'value' => null)));
       }

       $file = new File($this->dir . '/assets/files/' . $name);

       return $this->httpService->responesFile($file, $name,$file->getMimeType());


}




}
