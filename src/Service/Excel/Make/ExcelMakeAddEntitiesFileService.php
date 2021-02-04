<?php


namespace App\Service\Excel\Make;


use App\Entity\DataLoadingFileEntity;
use App\Entity\RasSchemaEntity;
use App\Service\Excel\Make\Interfaces\ExcelMakeAddEntitiesFileServiceInterface;
use App\Service\Excel\Read\ExcelDataLoadingFileReadService;
use App\Service\Excel\Read\Interfaces\ExcelRasSchemaReadServiceInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\KernelInterface;


class ExcelMakeAddEntitiesFileService implements ExcelMakeAddEntitiesFileServiceInterface
{
    /**
     * @var ExcelRasSchemaReadServiceInterface
     */
    private ExcelRasSchemaReadServiceInterface $excelRasSchemaReadService;
    /**
     * @var ExcelDataLoadingFileReadService
     */
    private ExcelDataLoadingFileReadService $dataLoadingFileReadService;
    /**
     * @var string
     */
    private string $dir;
    private LoggerInterface $logger;
    private array $arrayOfAddedPersons = array();

    /**
     * ExcelMakeAddEntitiesFileService constructor.
     * @param ExcelRasSchemaReadServiceInterface $excelRasSchemaReadService
     * @param ExcelDataLoadingFileReadService $dataLoadingFileReadService
     * @param KernelInterface $appKernel
     * @param LoggerInterface $logger
     */
    public function __construct(ExcelRasSchemaReadServiceInterface $excelRasSchemaReadService, ExcelDataLoadingFileReadService $dataLoadingFileReadService, KernelInterface $appKernel,  LoggerInterface $logger)
    {
        $this->excelRasSchemaReadService = $excelRasSchemaReadService;
        $this->dataLoadingFileReadService = $dataLoadingFileReadService;
        $this->dir = $appKernel->getProjectDir();
        $this->logger = $logger;
        $this->addedPersons = array();
    }

    /**
     * @param Request $request
     * @return string
     */
    public function make(Request $request): string
    {

        $fileName = str_replace('.', '_', uniqid('', true)) . ".xlsx";
        $result = json_decode($request->getContent(), true);
        if (is_null($result)) {
            return json_encode(array('error' => true, 'message' => 'Błąd systemu', 'value' => null));
        }

        if (!array_key_exists('rasSchema', $result) || !array_key_exists('dataLoading', $result)) {
            return json_encode(array('error' => true, 'message' => 'Błąd systemu', 'value' => null));
        }

        $arrayOfSheetRasSchema = $this->excelRasSchemaReadService->readFilePath($this->dir . '/assets/file/tmp/' . $result['rasSchema']);
        $dataLoadingSheetArray = $this->dataLoadingFileReadService->readFilePath($this->dir . '/assets/file/tmp/' . $result['dataLoading']);
        $spreadSheet = new Spreadsheet();


        $result = $this->generateSheets($spreadSheet, $arrayOfSheetRasSchema, $dataLoadingSheetArray);
        if (!$result) {
            return json_encode(array('error' => true, 'message' => 'Błąd systemu', 'value' => null));
        }
        try {
            $spreadSheet->removeSheetByIndex(0);
            $writer = new Xlsx($spreadSheet);
            $writer->save($this->dir . '/assets/files/' . $fileName);
        } catch (\PhpOffice\PhpSpreadsheet\Exception | Exception $e) {
            $this->logger->error($e->getCode() . ' -> ' . $e->getMessage());
            return json_encode(array('error' => true, 'message' => 'Błąd systemu', 'value' => null));
        }
        return json_encode(array('error' => false, 'message' => 'Plik został wygenerowany', 'value' => $fileName));
    }


    /**
     * @param Spreadsheet $spreadSheet
     * @param array $arrayOfSheetRasSchema
     * @param array $dataLoadingArray
     * @return bool
     */
    private function generateSheets(Spreadsheet $spreadSheet, array $arrayOfSheetRasSchema, array $dataLoadingArray): bool
    {
        $i = 0;

        foreach ($dataLoadingArray as $sheet) {
            if ($this->checkSheetArray($sheet) && $this->checkArrayOfSheetRasSchema($i, $arrayOfSheetRasSchema)) {
                try {
                    $workSheet = new Worksheet($spreadSheet, $sheet['name']);
                    $spreadSheet->addSheet($workSheet);
                    $this->makeHeader($workSheet);
                    $this->makeContent($workSheet,$arrayOfSheetRasSchema[$i]['value'], $sheet['value'] );
                } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
                    $this->logger->error($e->getCode() . ' -> ' . $e->getMessage());
                    return false;
                }
            }
            $i = $i + 1;
        }
        return true;
    }

    /**
     * @param int $index
     * @param array $arrayOfSheetRasSchema
     * @return bool
     */
    private function checkArrayOfSheetRasSchema(int $index, array $arrayOfSheetRasSchema): bool
    {
        if ($index >= sizeof($arrayOfSheetRasSchema)) {
            return false;
        }
        return $this->checkSheetArray($arrayOfSheetRasSchema[$index]);
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
            return false;
        }
        if (!array_key_exists('value', $sheet)) {
            return false;
        }
        return true;
    }

    /**`
     * @param Worksheet $workSheet
     */
    private function makeHeader(Worksheet $workSheet)
    {
        $workSheet->setCellValue('A1', 'Nagłówki funkcjonalności tworzenia podmiotów z danych w arkuszu');
        $workSheet->setCellValue('A2', 'EMITENT');
        $workSheet->setCellValue('B2', 'POSIADACZ');
        $workSheet->setCellValue('C2', 'NAZWA_PODMIOTU');
        $workSheet->setCellValue('D2', 'SHORT_NAME');
        $workSheet->setCellValue('E2', 'IMIE');
        $workSheet->setCellValue('F2', 'KOD_POCZTOWY');
        $workSheet->setCellValue('G2', 'MIASTO');
        $workSheet->setCellValue('H2', 'ULICA');
        $workSheet->setCellValue('I2', 'NUMER');
        $workSheet->setCellValue('J2', 'NIP');
        $workSheet->setCellValue('K2', 'LEI');
        $workSheet->setCellValue('L2', 'KOD_POCZTOWY_KOR');
        $workSheet->setCellValue('M2', 'MIASTO_KOR');
        $workSheet->setCellValue('N2', 'ULICA_KOR');
        $workSheet->setCellValue('O2', 'NUMER_KOR');
        $workSheet->setCellValue('P2', 'HASLO');
        $workSheet->setCellValue('Q2', 'TYP_OSOBY');
        $workSheet->setCellValue('R2', 'PESEL_REGON_RFI');
        $workSheet->setCellValue('S2', 'NUMER_DOK_TOZSAMOSCI');
        $workSheet->setCellValue('T2', 'STAWKA_PODATKOWA');
        $workSheet->setCellValue('U2', 'OSOBA_PUBLICZNA_PEP');
        $workSheet->setCellValue('V2', 'KLASA_KLIENTA');
        $workSheet->setCellValue('W2', 'TYP_KLIENTA');
        $workSheet->setCellValue('X2', 'KRAJ_KLIENTA');
        $workSheet->setCellValue('Y2', 'KATEGORIA_KLIENTA');
        $workSheet->setCellValue('Z2', 'NIE_ZNA_BENEFICJENTA');
        $workSheet->setCellValue('AA2', 'ODMAWIA_PODANIA_BENEFICJENTA');
        $workSheet->setCellValue('AB2', 'OCENA_RYZYKA');
        $workSheet->setCellValue('AC2', 'DATA_OCENY');
        $workSheet->setCellValue('AD2', 'NASTEPNA_OCENA');
        $workSheet->setCellValue('AE2', 'AKTYWNA_UMOWA');
        $workSheet->setCellValue('AF2', 'BENEFICJENCI');
        $workSheet->setCellValue('AG2', 'TELEFON_STACJONARNY');
        $workSheet->setCellValue('AH2', 'EMAIL');
        $workSheet->setCellValue('AI2', 'TELEFON_KOMORKOWY');
        $workSheet->setCellValue('AJ2', 'FAX');
        $workSheet->setCellValue('AK2', 'PRZETWARZANIE_DANYCH_OS');
        $workSheet->setCellValue('AL2', 'URZAD_SKARBOWY');
        $workSheet->setCellValue('AM2', 'SAD_REJESTROWY');
        $workSheet->setCellValue('AN2', 'KAPITAL_ZAKLADOWY');
        $workSheet->setCellValue('AO2', 'KAPITAL_OPLACONY');
        $workSheet->setCellValue('AP2', 'WALUTA');
        $workSheet->setCellValue('AQ2', 'RB_NUMER_RACHUNKU');
        $workSheet->setCellValue('AR2', 'RB_NAZWA_BANKU');
        $workSheet->setCellValue('AS2', 'RB_KOD_SWIFT');
        $workSheet->setCellValue('AT2', 'RB_KRAJ');
        $workSheet->setCellValue('AU2', 'RM_NUMER_RACHUNKU');
        $workSheet->setCellValue('AV2', 'RM_NAZWA_BANKU');
        $workSheet->setCellValue('AW2', 'RM_KOD_SWIFT');
        $workSheet->setCellValue('AX2', 'RM_KRAJ');
        $workSheet->setCellValue('AY2', 'NR_KRS');
        $workSheet->setCellValue('AZ2', 'DATA_REJESTRACJI');
        $workSheet->setCellValue('BA2', 'ZGODA_KONTAKT_EMAIL');
        $workSheet->setCellValue('BB2', 'REPREZENTANCI');
        $workSheet->setCellValue('BC2', 'PELNOMOCNICY');
    }


    /**
     * @param Worksheet $workSheet
     * @param array $arrayOfRasSchema
     * @param array $dataLoadingArray
     */
    private function makeContent(Worksheet $workSheet, array $arrayOfRasSchema, array $dataLoadingArray)
    {
        $i = 0;
        $row = 3;
        $this->addedPersons =  array();
        $maxSize = sizeof($arrayOfRasSchema);
        foreach ($dataLoadingArray as $item) {
            $result = false;
            if ($i < $maxSize) {
                $result = $this->makerRow($workSheet, $item, $arrayOfRasSchema[$i], $row);
            }
            $i = $i + 1;
            if ($result) {
                $row = $row + 1;
            }

        }
    }

    /**
     * @param Worksheet $workSheet
     * @param DataLoadingFileEntity $dataLoadingFileEntity
     * @param RasSchemaEntity $rasSchemaEntity
     * @param int $row
     * @return bool
     */
    private function makerRow(Worksheet $workSheet, DataLoadingFileEntity $dataLoadingFileEntity, RasSchemaEntity $rasSchemaEntity, int $row): bool
    {
        if (strcmp($dataLoadingFileEntity->getStatus(), 'ER: Brak klienta w bazie') !== 0) {
            return false;
        }

        if($this->checkPersonInArray($rasSchemaEntity)){
            return false;
        }
        $personType = $this->personType($rasSchemaEntity);
        $workSheet->setCellValue('B' . $row, 'T');
        $workSheet->setCellValue('C' . $row, $rasSchemaEntity->getEntityName());
        $workSheet->setCellValue('E' . $row, $rasSchemaEntity->getName());
        $workSheet->setCellValue('F' . $row, $rasSchemaEntity->getPostalCode());
        $workSheet->setCellValue('G' . $row, $rasSchemaEntity->getCity());
        $workSheet->setCellValue('H' . $row, $rasSchemaEntity->getStreet());
        $workSheet->setCellValue('I' . $row, $rasSchemaEntity->getHouseNumber());
        $workSheet->setCellValue('L' . $row, $rasSchemaEntity->getCorrespondencePostalCode());
        $workSheet->setCellValue('M' . $row, $rasSchemaEntity->getCorrespondenceCity());
        $workSheet->setCellValue('N' . $row, $rasSchemaEntity->getCorrespondenceStreet());
        $workSheet->setCellValue('O' . $row, $rasSchemaEntity->getCorrespondenceHouseNumber());
        $workSheet->setCellValue('Q' . $row, $personType);
        $workSheet->setCellValue('R' . $row, $rasSchemaEntity->getPeselRegonRfi());
        $workSheet->setCellValue('S' . $row, $rasSchemaEntity->getIdNumber());
        $workSheet->setCellValue('V' . $row, $rasSchemaEntity->getResident());
        $workSheet->setCellValue('W' . $row, $this->clientType($personType, $rasSchemaEntity));
        $workSheet->setCellValue('X' . $row, $rasSchemaEntity->getCountryCode());
        $workSheet->setCellValue('AG' . $row, $rasSchemaEntity->getLandlinePhone());
        $workSheet->setCellValue('AH' . $row, $rasSchemaEntity->getEmail());
        $workSheet->setCellValue('AI' . $row, $rasSchemaEntity->getMobilePhone());
        if(strlen($rasSchemaEntity->getPeselRegonRfi()) === 0){
            array_push($this->arrayOfAddedPersons, trim($rasSchemaEntity->getEntityName()).trim($rasSchemaEntity->getName()).trim($rasSchemaEntity->getCity()).trim($rasSchemaEntity->getStreet()).trim($rasSchemaEntity->getHouseNumber()));
        }else{
            array_push($this->arrayOfAddedPersons, $rasSchemaEntity->getPeselRegonRfi());
        }

        return true;
    }

    /**
     * @param RasSchemaEntity $rasSchemaEntity
     * @return string
     */
    private function personType(RasSchemaEntity $rasSchemaEntity): string
    {
        if ((strcmp($rasSchemaEntity->getCountryCode(), 'PL') === 0) || (strcmp($rasSchemaEntity->getCountryCode(), 'pl') === 0)) {
            if (preg_match('/^[0-9]{11}$/', $rasSchemaEntity->getPeselRegonRfi())) {
                return 'F';
            }
            if(strlen($rasSchemaEntity->getPeselRegonRfi()) > 0){
                return "P";
            }
        }

        if (strlen($rasSchemaEntity->getName()) === 0) {
            return "P";
        }

        if (strlen($rasSchemaEntity->getName()) > 0) {
            return "F";
        }

        return 'P';
    }

    /**
     * @param string $personType
     * @param RasSchemaEntity $rasSchemaEntity
     * @return string
     */
    private function clientType(string $personType, RasSchemaEntity $rasSchemaEntity): string
    {
        if ((strcmp($rasSchemaEntity->getCountryCode(), 'PL') === 0) || (strcmp($rasSchemaEntity->getCountryCode(), 'pl') === 0)) {
            if (strcmp($personType, 'F') === 0) {
                return 'O_FIZ';
            }
            return 'P_PNF';
        }

        if (strcmp($personType, 'F') === 0) {
            return 'Z_FIZ';
        }
        return 'Z_PNF';
    }


    /**
     * this function check repeated values
     * @param RasSchemaEntity $rasSchemaEntity
     * @return bool
     */
    private function checkPersonInArray(RasSchemaEntity $rasSchemaEntity): bool
    {

       if(strlen($rasSchemaEntity->getPeselRegonRfi())> 0){
            if(in_array($rasSchemaEntity->getPeselRegonRfi(), $this->arrayOfAddedPersons)){
                return true;
            }
       }
        if(in_array(trim($rasSchemaEntity->getEntityName()).trim($rasSchemaEntity->getName()).trim($rasSchemaEntity->getCity()).trim($rasSchemaEntity->getStreet()).trim($rasSchemaEntity->getHouseNumber()), $this->arrayOfAddedPersons)){
            return true;
        }
        return false;
    }

}
