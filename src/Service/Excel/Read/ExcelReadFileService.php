<?php


namespace App\Service\Excel\Read;

use App\Service\Excel\Read\Interfaces\ExcelReadFileServiceInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

class ExcelReadFileService implements ExcelReadFileServiceInterface
{
    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * ReadFileService constructor.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {

        $this->logger = $logger;
    }

    /**
     * Function read file and make array of RasSchema entity
     * @param Request $request
     * @return array|null
     */
    public function read(Request $request): ?array
    {

        $file = $request->files->get('file');
        if (is_null($file)) {
            return null;
        }
        return $this->readFile($file, $file->getClientOriginalName());
    }

    /**
     * @param string $path
     * @return array|null
     */
    public function readPath(string $path): ?array
    {
        $file = new File($path);
        if (is_null($file)) {
            return null;
        }
        return $this->readFile($file, $file->getFilename());
    }

    /**
     * @param File $file
     * @param string $fileName
     * @return array|null
     */
    private function readFile(File $file, string $fileName): ?array
    {
        $pieces = explode(".", $fileName);
        ucfirst(strtolower(end($pieces)));
        $inputFileType = ucfirst(strtolower(end($pieces)));

        if (!$this->checkFormat($inputFileType)) {
            return null;
        }
        try {
            $reader = IOFactory::createReader($inputFileType);
            $spreadsheet = $reader->load($file);
            return $this->loadData($spreadsheet);

        } catch (Exception $e) {
            $this->logger->error($e->getCode() . ' -> ' . $e->getMessage());
            return null;
        }
    }

    /**
     * function check if format is good
     * @param string $format
     * @return bool
     */
    private function checkFormat(string $format): bool
    {
        return strcmp('Xls', $format) === 0 || strcmp('Xlsx', $format) === 0;
    }

    /**
     * @param Spreadsheet $spreadsheet
     * @return array
     */
    private function loadData(Spreadsheet $spreadsheet): array
    {
        $arrayOfSheets = array();
        $sheets = $spreadsheet->getAllSheets();
        $arrayOfNames = $spreadsheet->getSheetNames();
        $i = 0;
        foreach ($sheets as $sheet){
            $item = array('name' => $arrayOfNames[$i], 'value' => $sheet->toArray('', true, true, true));
            array_push($arrayOfSheets, $item);
            $i = $i + 1;
        }

        return $arrayOfSheets;
    }
}
