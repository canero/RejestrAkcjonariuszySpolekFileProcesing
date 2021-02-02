<?php


namespace App\Service\Excel\Check;


use App\Service\Excel\Make\Interfaces\ExcelRasSchemaContentInterface;
use App\Service\Validation\Interfaces\PeselValidationServiceInterface;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CheckExcelRasSchemaService implements ExcelRasSchemaContentInterface
{
    /**
     * @var PeselValidationServiceInterface
     */
    private PeselValidationServiceInterface $peselValidationService;

    /**
     * CheckExcelRasSchemaService constructor.
     * @param PeselValidationServiceInterface $peselValidationService
     */
    public function __construct(PeselValidationServiceInterface $peselValidationService)
    {

        $this->peselValidationService = $peselValidationService;
    }

    /**
     * @param Worksheet $workSheet
     */
    public function makeHeader(Worksheet $workSheet)
    {
        $workSheet->setCellValue('A20', 'Lp.');
        $workSheet->setCellValue('B20', 'NAZWISKO / NAZWA PODMIOTU (pełna)');
        $workSheet->setCellValue('C20', 'IMIĘ');
        $workSheet->setCellValue('D20', 'KOD KRAJU');
        $workSheet->setCellValue('E20', 'Rezydent / Nierezydent');
        $workSheet->setCellValue('F20', 'KOD POCZTOWY');
        $workSheet->setCellValue('G20', 'MIASTO');
        $workSheet->setCellValue('H20', 'ULICA');
        $workSheet->setCellValue('I20', 'NUMER');
        $workSheet->setCellValue('J20', 'KOR KOD POCZTOWY');
        $workSheet->setCellValue('K20', 'KOR MIASTO');
        $workSheet->setCellValue('L20', 'KOR ULICA');
        $workSheet->setCellValue('M20', 'KOR NUMER');
        $workSheet->setCellValue('N20', 'PESEL REGON RFI');
        $workSheet->setCellValue('O20', 'NUMER DOKUMENTU TOŻSAMOŚCI');
        $workSheet->setCellValue('P20', 'TELEFON STACJONARNY');
        $workSheet->setCellValue('Q20', 'EMAIL');
        $workSheet->setCellValue('R20', 'TELEFON KOMÓRKOWY');
        $workSheet->setCellValue('S20', 'SERIA');
        $workSheet->setCellValue('T20', 'LICZBA AKCJI');
        $workSheet->setCellValue('U20', 'NUMERY OD');
        $workSheet->setCellValue('V20', 'NUMERY DO');
        $workSheet->setCellValue('W20', 'WARTOŚĆ NOMINALNA');
        $workSheet->setCellValue('X20', 'Błędy');
    }

    /**
     * @param Worksheet $workSheet
     * @param array $arrayOfRasSchema
     * @param array $params
     */
    public function makeContent(Worksheet $workSheet, array $arrayOfRasSchema, array $params = [])
    {
        $i = 0;
        $row = 21;
        $countError = 0;
        $numberOfActions = 0;
        usort($arrayOfRasSchema, fn($a, $b) => $a->getNumberFrom() <=> $b->getNumberFrom());


        foreach ($arrayOfRasSchema as $item) {
            $errorList = array();
            $workSheet->setCellValue('A' . $row, $i + 1);
            array_push($errorList, $this->setCelleError($item->getEntityName(), $workSheet, 'B' . $row, 'Brak: NAZWISKO / NAZWA PODMIOTU (pełna); '));
            $workSheet->setCellValue('C' . $row, $item->getName());
            array_push($errorList, $this->setCelleError($item->getCountryCode(), $workSheet, 'D' . $row, 'Błąd: KOD KRAJU; ', false, strlen(trim($item->getCountryCode())) != 2));
            array_push($errorList, $this->setCelleError($item->getResident(), $workSheet, 'E' . $row, 'Błąd: Rezydent / Nierezydent; ', false, $this->checkResident($item->getResident())));
            array_push($errorList, $this->setCelleError($item->getPostalCode(), $workSheet, 'F' . $row, 'Brak: KOD POCZTOWY; '));
            array_push($errorList, $this->setCelleError($item->getCity(), $workSheet, 'G' . $row, 'Brak: MIASTO; '));
            array_push($errorList, $this->setCelleError($item->getStreet(), $workSheet, 'H' . $row, 'Brak: ULICA; '));
            array_push($errorList, $this->setCelleError($item->getHouseNumber(), $workSheet, 'I' . $row, 'Brak: NUMER; '));
            $workSheet->setCellValue('J' . $row, $item->getCorrespondencePostalCode());
            $workSheet->setCellValue('K' . $row, $item->getCorrespondenceCity());
            $workSheet->setCellValue('L' . $row, $item->getCorrespondenceStreet());
            $workSheet->setCellValue('M' . $row, $item->getCorrespondenceHouseNumber());
            array_push($errorList, $this->setCelleError($item->getPeselRegonRfi(), $workSheet, 'N' . $row, 'Błąd: PESEL REGON RFI; ', false, $this->checkPesel($item->getPeselRegonRfi())));
            $workSheet->setCellValue('O' . $row, $item->getIdNumber());
            $workSheet->setCellValue('P' . $row, $item->getLandlinePhone());
            $workSheet->setCellValue('Q' . $row, $item->getEmail());
            $workSheet->setCellValue('R' . $row, $item->getMobilePhone());
            $workSheet->setCellValue('S' . $row, $item->getSeries());
            $array = $this->checkNumberFrom($item->getNumberFrom(), $item->getNumberTo(), $item->getTheNumberOfActions(), sizeof($arrayOfRasSchema) > $i + 1 ? $arrayOfRasSchema[$i + 1]->getNumberFrom() : 0);
            array_push($errorList, $this->setCelleError($item->getTheNumberOfActions(), $workSheet, 'T' . $row, 'Błąd: NIEPRAWIDŁOWA LICZBA AKCJI; ', false, in_array('noDataNumberOfActions', $array) || in_array('numberOfActions', $array)));
            $message = in_array('noDataFrom', $array) ? 'Brak: NUMERY OD; ' : '';
            $message .= in_array('fromGreater', $array) ? 'Błąd: NUMERY OD JEST WIĘKSZY NIŻ NUMERY DO; ' : '';
            array_push($errorList, $this->setCelleError($item->getNumberFrom(), $workSheet, 'U' . $row, $message, false, strlen($message) > 0));
            $message = in_array('noDataTo', $array) ? 'Brak: NUMERY DO; ' : '';
            $message .= in_array('continuityOfAction', $array) ? 'Uwaga: BRAK CIĄGŁOŚCI NUMERACJI; ' : '';
            array_push($errorList, $this->setCelleError($item->getNumberTo(), $workSheet, 'V' . $row, $message, false, strlen($message) > 0));
            array_push($errorList, $this->setCelleError($item->getNominalValue(), $workSheet, 'W' . $row, 'Brak: WARTOŚĆ NOMINALNA; '));
            $message = '';
            foreach ($errorList as $error) {
                $message .= $error['message'];
                $countError = $countError + $error['error'];
            }
            $workSheet->setCellValue('X' . $row, $message);
            $numberOfActions = $this->sumNumberOfActions($item->getTheNumberOfActions(), $numberOfActions);
            $i = $i + 1;
            $row = $row + 1;
        }
        $workSheet->setCellValue('S' . $row, 'Suma:');
        $workSheet->setCellValue('T' . $row, $numberOfActions);
        $workSheet->setCellValue('A1', 'Liczba błędów: ' . $countError);
    }


    /**
     * @param string $numberOfActions
     * @param int $allNumberOfActions
     * @return int
     */
    private function sumNumberOfActions(string $numberOfActions, int $allNumberOfActions): int
    {
        if (strlen($numberOfActions) === 0) {
            return $allNumberOfActions;
        }
        $actions = intval(preg_replace("/[^0-9]/", "", $numberOfActions));
        return $allNumberOfActions + $actions;
    }


    /**
     * @param string $resident
     * @return bool
     */
    private function checkResident(string $resident): bool
    {
        if (strcmp(strtoupper(trim($resident)), "R") === 0) {
            return false;
        }

        if (strcmp(strtoupper(trim($resident)), "N") === 0) {
            return false;
        }
        return true;
    }

    /**
     * funkcja sprawdza numery. Sprawdza czy liczby istnieją, czy liczba  od jest większa od liczby do, czy liczba akcji się zgadza, czy ackje są po kolei
     * @param string $numberFrom
     * @param string $numberTo
     * @param string $numberOfActions
     * @param string $numberFromNext
     * @return array
     */
    private function checkNumberFrom(string $numberFrom, string $numberTo, string $numberOfActions, string $numberFromNext = '0'): array
    {
        $arrayOfError = array();
        if (strlen($numberFrom) === 0) {
            array_push($arrayOfError, 'noDataFrom');
        }

        if (strlen($numberTo) === 0) {
            array_push($arrayOfError, 'noDataTo');
        }

        if (strlen($numberOfActions) === 0) {
            array_push($arrayOfError, 'noDataNumberOfActions');
        }

        if (strlen($numberFromNext) === 0) {
            array_push($arrayOfError, 'noDataNextFrom');
        }


        $from = strlen($numberFrom) !== 0 ? intval(preg_replace("/[^0-9]/", "", $numberFrom)) : null;
        $to = strlen($numberTo) !== 0 ? intval(preg_replace("/[^0-9]/", "", $numberTo)) : null;
        $actions = strlen($numberOfActions) != 0 ? intval(preg_replace("/[^0-9]/", "", $numberOfActions)) : null;
        $fromNext = strlen($numberFromNext) !== 0 ? intval(preg_replace("/[^0-9]/", "", $numberFromNext)) : null;
        if (!is_null($from) && !is_null($to)) {
            if ($from >= $to) {
                array_push($arrayOfError, 'fromGreater');
            }
        }
        if (!is_null($from) && !is_null($to) && !is_null($actions)) {
            if ($to - ($from - 1) !== $actions) {
                array_push($arrayOfError, 'numberOfActions');
            }
        }

        if ($fromNext != 0 && !is_null($fromNext) && !is_null($to)) {
            if ($to + 1 !== $fromNext) {
                array_push($arrayOfError, 'continuityOfAction');
            }
        }

        return $arrayOfError;
    }


    /**
     * @param string $pesel
     * @return bool
     */
    private function checkPesel(string $pesel): bool
    {
        if (strlen(trim($pesel)) === 0) return true;
        if (!strcmp(strtoupper($pesel), 'pl')) return false;
        if (strlen(trim($pesel)) !== 11) return false;
        return !$this->peselValidationService->verify($pesel);

    }

    /**
     * @param string $value
     * @param Worksheet $workSheet
     * @param string $cell
     * @param string $message
     * @param bool $errorNoData
     * @param bool $error
     * @return array
     */
    private function setCelleError(string $value, Worksheet $workSheet, string $cell, string $message, bool $errorNoData = true, bool $error = false): array
    {
        $workSheet->setCellValue($cell, $value);
        $errorCheck = $error;
        if ($errorNoData === true) {
            $errorCheck = strlen($value) === 0;
        }
        if ($errorCheck) {
            $workSheet->getStyle($cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF0000');
            return array('error' => 1, 'message' => $message);
        }
        return array('error' => 0, 'message' => '');
    }


}
