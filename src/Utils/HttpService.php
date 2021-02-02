<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response;

class HttpService{

     /**
     * Function to create JSON response of request
     *
     * @param string $content of response
     * @return Response
     */
    public function responseJSON($content) :Response{
        $response = new Response();
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }

    /**
     * Function to create Text response of request
     *
     * @param string $content of response
     * @return Response
     */
    public function responseText($content, int $statusCode) :Response{
        $response = new Response();
        $response->setContent($content);
        $response->headers->set('Content-Type', 'text/plain');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->setStatusCode( $statusCode);
        return $response;
    }


    
    /**
     * Function to create PDF response of request
     *
     * @param string $content of response
     * @return Response
     */
    public function responsePDF($content) :Response{
        $response = new Response();
        $response->setContent($content);
        $response->headers->set('Content-Type', 'application/pdf');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Disposition', "attachment; filename=test.pdf");
        return $response;
    }

    /**
     * Function to create Image response of request
     *
     * @param string $content of response
     * @return Response
     */
    public function responseImage($content, $filename) :Response{
        $response = new BinaryFileResponse($content);
        $response->headers->set('Content-Type', 'image/png');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Disposition', "attachment; filename=".$filename);
        return $response;
    }

    public function responesFile($content, $filename, $mimeType){
        $response = new BinaryFileResponse($content);
        $response->headers->set('Content-Type', $mimeType);
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Disposition', "attachment; filename=".$filename);
        return $response;
    }    

    public function responseZip($zip_name){
        $response = new BinaryFileResponse($zip_name);
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Disposition', "attachment; filename=" .$zip_name);
        $response->deleteFileAfterSend(true);
        return $response;
    }

}