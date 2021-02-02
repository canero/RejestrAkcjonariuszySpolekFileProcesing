<?php


namespace App\Service\Excel\Make;


use App\Service\Excel\Make\Interfaces\ExcelRasSchemaContentInterface;
use App\Service\Excel\Make\Interfaces\ExcelRasShameServiceInterface;
use App\Service\Excel\Read\Interfaces\ExcelRasSchemaReadServiceInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;

class ExcelRasShameService implements ExcelRasShameServiceInterface
{
    private ExcelRasSchemaReadServiceInterface $excelRasSchemaReadService;
    private string $dir;
    private LoggerInterface $logger;

    /**
     * ExcelDataLoadingFileService constructor.
     * @param ExcelRasSchemaReadServiceInterface $excelRasSchemaReadService
     * @param KernelInterface $appKernel
     * @param LoggerInterface $logger
     */
    public function __construct(ExcelRasSchemaReadServiceInterface $excelRasSchemaReadService, KernelInterface $appKernel, LoggerInterface $logger)
    {
        $this->excelRasSchemaReadService = $excelRasSchemaReadService;
        $this->dir = $appKernel->getProjectDir();
        $this->logger = $logger;
    }

    /**
     * @param Request $request
     * @param ExcelRasSchemaContentInterface $rasSchemaContent
     * @param array $params
     * @return string
     */
    public function generate(Request $request, ExcelRasSchemaContentInterface $rasSchemaContent, array $params = []):string
    {
        $fileName = str_replace('.', '_', uniqid('', true)) . ".xlsx";
        $arrayOfSheetRasSchema = $this->excelRasSchemaReadService->read($request);

        if (is_null($arrayOfSheetRasSchema)) {
            return json_encode(array('error' => true, 'message' => 'Błąd systemu', 'value' => null));
        }

        $spreadSheet = new Spreadsheet();
        $result = $this->generateSheets($spreadSheet, $arrayOfSheetRasSchema, $rasSchemaContent, $params);

        if (!$result) {
            return json_encode(array('error' => true, 'message' => 'Błąd systemu', 'value' => null));
        }


        try {
            $spreadSheet->removeSheetByIndex(0);
            $writer = new Xlsx($spreadSheet);
            $writer->save($this->dir . '/assets/files/' . $fileName);
        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            $this->logger->error($e->getCode() . ' -> ' . $e->getMessage());
            return json_encode(array('error' => true, 'message' => 'Błąd systemu', 'value' => null));
        }
        return json_encode(array('error' => false, 'message' => 'Plik został wygenerowany', 'value' => $fileName));
    }

    /**
     * @param Spreadsheet $spreadSheet
     * @param array $arrayOfSheetRasSchema
     * @param ExcelRasSchemaContentInterface $rasSchemaContent
     * @param array $params
     * @return bool
     */
    private function generateSheets(Spreadsheet $spreadSheet, array $arrayOfSheetRasSchema,  ExcelRasSchemaContentInterface $rasSchemaContent, array $params = []): bool
    {
        foreach ($arrayOfSheetRasSchema as $sheet) {
            if ($this->checkSheetArray($sheet)) {
                try {
                    $workSheet = new Worksheet($spreadSheet, $sheet['name']);
                    $spreadSheet->addSheet($workSheet);
                    $rasSchemaContent->makeHeader($workSheet);
                    $rasSchemaContent->makeContent($workSheet, $sheet['value'], $params);
                } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                    $this->logger->error($e->getCode() . ' -> ' . $e->getMessage());
                    return false;
                }
            }
        }
        return true;
    }


    /**
     * @param $sheet
     * @return bool
     */
    private function checkSheetArray($sheet): bool
    {
        if (!is_array($sheet)) {
            return false;
        }
        if (!array_key_exists('name', $sheet)) {
            return fasel;
        }
        if (!array_key_exists('value', $sheet)) {
            return fasel;
        }
        return true;
    }
}
