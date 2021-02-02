<?php


namespace App\Service\Excel\Make;

use App\Service\Excel\Make\Interfaces\ExcelRasSchemaContentInterface;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExcelDataLoadingFileService implements ExcelRasSchemaContentInterface
{



    /**
     * @param Worksheet $workSheet
     */
    public function makeHeader(Worksheet $workSheet)
    {
        $workSheet->setCellValue('A1', 'Wczytywanie danych do przydziału dla instrumentu ');
        $workSheet->setCellValue('A3', 'Podmiot_ID');
        $workSheet->setCellValue('B3', 'Status');
        $workSheet->setCellValue('C3', 'Nazwa/Nazwisko');
        $workSheet->setCellValue('D3', 'Imię');
        $workSheet->setCellValue('E3', 'PESEL_REGON_RFI');
        $workSheet->setCellValue('F3', 'Ilość');
        $workSheet->setCellValue('G3', 'Cena nabycia');
        $workSheet->setCellValue('H3', 'Nr od');
        $workSheet->setCellValue('I3', 'Nr do');
        $workSheet->setCellValue('J3', 'Rachunek bankowy');
        $workSheet->setCellValue('K3', 'Rachunek maklerski');
    }


    /**
     * @param Worksheet $workSheet
     * @param array $arrayOfRasSchema
     * @param array $params
     */
    public function makeContent(Worksheet $workSheet, array $arrayOfRasSchema, array $params = [])
    {
        $i = 1;
        $row = 4;
        $withNumber = false;
        if(array_key_exists('withNumber', $params)){
            $withNumber = $params['withNumber'];
        }
        if ($withNumber === true) {
            usort($arrayOfRasSchema, fn($a, $b) => $a->getNumberFrom() <=> $b->getNumberFrom());
        }

        foreach ($arrayOfRasSchema as $item) {
            $workSheet->setCellValue('A' . $row, '');
            $workSheet->setCellValue('B' . $row, '');
            $workSheet->setCellValue('C' . $row, $item->getEntityName());
            $workSheet->setCellValue('D' . $row, $item->getName());
            $workSheet->setCellValue('E' . $row, $item->getPeselRegonRfi());
            $workSheet->setCellValue('F' . $row, $withNumber === true ? preg_replace("/[^0-9]/", "", $item->getTheNumberOfActions()) : 1);
            $workSheet->setCellValue('G' . $row, '');
            $workSheet->setCellValue('H' . $row, $withNumber === true ? preg_replace("/[^0-9]/", "", $item->getNumberFrom()) : $i);
            $workSheet->setCellValue('I' . $row, $withNumber === true ? preg_replace("/[^0-9]/", "", $item->getNumberTo()) : $i);
            $workSheet->setCellValue('J' . $row, '');
            $workSheet->setCellValue('K' . $row, '');
            $i = $i + 1;
            $row = $row + 1;
        }
    }




}
