<?php


namespace App\Service\File\Upload;


use App\Service\File\Upload\Interfaces\FileUploadServiceInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class FileUploadService implements FileUploadServiceInterface
{
    private string $dir;

    /**
     * FileUploadService constructor.
     * @param KernelInterface $appKernel
     */
    public function __construct(KernelInterface $appKernel){
        $this->dir = $appKernel->getProjectDir();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function upload(Request $request):string{
        $file = $request->files->get('file');
        if (is_null($file)) {
            return json_encode(array('error' => true, 'message' => 'Błąd systemu', 'value' => null));
        }

        $pieces = explode(".", $file->getClientOriginalName());
        $fileName = str_replace('.', '_', uniqid('', true)) . "." . end($pieces);
        $file->move($this->dir . '/assets/files/tmp/' , $fileName);
        return json_encode(array('error' => false, 'message' => 'Plik został uploadowany poprawnie', 'value' => $fileName));
    }
}
