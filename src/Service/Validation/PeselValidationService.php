<?php


namespace App\Service\Validation;


use App\Service\Validation\Interfaces\PeselValidationServiceInterface;

class PeselValidationService implements PeselValidationServiceInterface
{

    private string $pesel;
    private array $response;
    private string $dataPodana;

    public function __construct()
    {
        $this->pesel = '';
        $this->response = array();
        $this->dataPodana = '';
    }

    public function verify(string $pesel): bool
    {

// Kolejne metody zwracają kolejne cechy podanego numeru pesel
        $this->pesel = $pesel;

        if (!$this->is11Integer()) return false;    // sprawdzenie czy ma 11 cyfr
        if (!$this->checkSum()) return false;         // sprawdzanie sumy kontrolnej
        if (!$this->verifyBirtDate()) return false;// sprawdzanie daty urodzenia
        if (!$this->getAge()) return false;        // obliczanie wieku
        $this->getSex();        // określenie płci

        return true;

    }

    private function is11Integer(): bool
    {

// Czy składa sie z 11 cyfr

        $this->response['has11Digits'] = true;
        if (!preg_match('/^[0-9]{11}$/', $this->pesel)) {
            $this->response['has11Digits'] = false;
            return false;
        }
        return true;
    }

    private function checkSum(): bool
    {

// Klasyczna walidacja wg algorytmu Luhna z wagami stosowanymi przez MSWiA
// Na podstawie: http://phpedia.pl/wiki/Walidacja_numeru_PESEL

        if (empty($this->response['has11Digits'])) return false;

// tablica z odpowiednimi wagami

        $arrWagi = array(1, 3, 7, 9, 1, 3, 7, 9, 1, 3);
        $intSum = 0;

//mnożymy każdy ze znaków przez wagę i sumujemy wszystko

        for ($i = 0; $i < 10; $i++) {
            $intSum += $arrWagi[$i] * $this->pesel[$i];
        }

//obliczamy sumę kontrolną i porównujemy ją z ostatnią cyfrą.

        $int = 10 - $intSum % 10;
        $intControlNr = ($int == 10) ? 0 : $int;

//sprawdzamy czy taka sama suma kontrolna jest w ciągu

        if ($intControlNr == $this->pesel[10]) {

            $this->response['isCheckSumOk'] = true;
            return true;
        }

        $this->response['isCheckSumOk'] = false;
        return false;

    }

    private function verifyBirtDate(): bool
    {

// Walidacja daty urodzenia wg istnienia dnia w kalendarzu
// Ze względu na działanie systemu PESEL na 5 stuleci
// dopisuje się 80, 20, 40, 60 do daty w odpowiednich stuleciach

        if (empty($this->response['has11Digits'])) return false;

//  Budowa tablicy możliwych wartości miesiąca

        $miesiac = substr($this->pesel, 2, 2);
        $arrMiesiace = array('01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12');
        $arrDodatkoweMiesiace = array(0, 80, 20, 40, 60);
        $stulecie = 0;
        foreach ($arrDodatkoweMiesiace as $miesiacDodatkowy) {
            $arrMiesiaceBazowe = range(1, 12);
            foreach ($arrMiesiaceBazowe as $miesiacBazowy) {
                $arrMiesiace[] = $miesiacDodatkowy + $miesiacBazowy;
            }
        }

        if (!in_array($miesiac, $arrMiesiace)) {
            $this->response['isBirthDateValid'] = false;
            return false;
        }

// Ustalanie faktycznego miesiąca i stulecia

        if (substr($miesiac, 0, 1) == '0' || substr($miesiac, 0, 1) == '1') $stulecie = 1900;
        if (substr($miesiac, 0, 1) == '8' || substr($miesiac, 0, 1) == '9') $stulecie = 1800;
        if (substr($miesiac, 0, 1) == '2' || substr($miesiac, 0, 1) == '3') $stulecie = 2000;
        if (substr($miesiac, 0, 1) == '5' || substr($miesiac, 0, 1) == '4') $stulecie = 2100;
        if (substr($miesiac, 0, 1) == '6' || substr($miesiac, 0, 1) == '7') $stulecie = 2200;

        if ($stulecie == '2000') $miesiac = $miesiac - 20;
        if ($stulecie == '1800') $miesiac = $miesiac - 80;
        if ($stulecie == '2100') $miesiac = $miesiac - 40;
        if ($stulecie == '2200') $miesiac = $miesiac - 60;

// Walidacja liczby dni w miesiącu

        $rok = $stulecie + substr($this->pesel, 0, 2);
        $maxDays = cal_days_in_month(CAL_GREGORIAN, $miesiac, $rok);

        $dzien = substr($this->pesel, 4, 2);
        $this->dataPodana = "$rok-$miesiac-$dzien";

        if ($dzien > $maxDays) {
            $this->response['isBirthDateValid'] = false;
            return false;
        }
        if ($dzien <= 0) {
            $this->response['isBirthDateValid'] = false;
            return false;
        }
        $this->response['isBirthDateValid'] = true;
        $this->response['birthDate'] = date_format(date_create($this->dataPodana), 'Y-m-d');
        return true;

    }

    private function getAge(): bool
    {

// Sprawdzenie wieku i daty urodzenia osoby, a także czy jest to osoba, która się jeszcze nie urodziła

        if ($this->response['has11Digits'] <> true) return false;
        if (empty($this->response['isBirthDateValid'])) return false;

        $dataAktualna = date_create();
        $dataPodana = date_create($this->dataPodana);
        $interval = date_diff($dataAktualna, $dataPodana)->format('%R%a');    // ile dni

        if ($interval < 0) {
            $this->response['bornAlready'] = true;
            $this->response['personAgeYears'] = -intval(date_diff($dataAktualna, $dataPodana)->format('%R%y'));
            $this->response['personAgeMonths'] = -intval(date_diff($dataAktualna, $dataPodana)->format('%R%m'));
            $this->response['personAgeDays'] = -intval(date_diff($dataAktualna, $dataPodana)->format('%R%d'));
            if ($this->response['personAgeYears'] >= 18) $this->response['fullAge'] = true;
            if ($this->response['personAgeYears'] < 18) $this->response['fullAge'] = false;
            return true;
        } else {
            $this->response['bornAlready'] = false;
            $this->response['possibleBornInYears'] = intval(date_diff($dataAktualna, $dataPodana)->format('%y'));
            $this->response['possibleBornInMonths'] = intval(date_diff($dataAktualna, $dataPodana)->format('%m'));
            $this->response['possibleBornInDays'] = intval(date_diff($dataAktualna, $dataPodana)->format('%d'));
            return false;
        }

    }

    private function getSex()
    {

// Sprawdzenie płci. 10 cyfra nieparzysta = mężczyzna, parzysta = kobieta

        if ($this->response['has11Digits'] <> true) return;
        if (empty($this->response['isBirthDateValid'])) return;
        $dziesiata = $this->pesel[9];
        if (($dziesiata % 2 == 0) == true) {
            $this->response['personSex'] = 'F';
            return;
        }
        $this->response['personSex'] = 'M';
    }

    public function returnResponse(): array
    {

        return $this->response;

    }

}
