<?php


namespace App\Entity;

class RasSchemaEntity
{
    private string $id;
    private string $entityName;
    private string $name;
    private string $countryCode;
    private string $resident;
    private string $postalCode;
    private string $city;
    private string $street;
    private string $houseNumber;
    private string $correspondencePostalCode;
    private string $correspondenceCity;
    private string $correspondenceStreet;
    private string $correspondenceHouseNumber;
    private string $peselRegonRfi;
    private string $idNumber;
    private string $landlinePhone;
    private string $email;
    private string $mobilePhone;
    private string $series;
    private string $theNumberOfActions;
    private string $numberFrom;
    private string $numberTo;
    private string $nominalValue;

    /**
     * RasSchemaEntity constructor.
     * @param string $id
     * @param string $entityName
     * @param string $name
     * @param string $countryCode
     * @param string $resident
     * @param string $postalCode
     * @param string $city
     * @param string $street
     * @param string $houseNumber
     * @param string $correspondencePostalCode
     * @param string $correspondenceCity
     * @param string $correspondenceStreet
     * @param string $correspondenceHouseNumber
     * @param string $peselRegonRfi
     * @param string $idNumber
     * @param string $landlinePhone
     * @param string $email
     * @param string $mobilePhone
     * @param string $series
     * @param string $theNumberOfActions
     * @param string $numberFrom
     * @param string $numberTo
     * @param string $nominalValue
     */
    public function __construct(string $id, string $entityName, string $name, string $countryCode, string $resident, string $postalCode, string $city, string $street, string $houseNumber, string $correspondencePostalCode, string $correspondenceCity, string $correspondenceStreet, string $correspondenceHouseNumber, string $peselRegonRfi, string $idNumber, string $landlinePhone, string $email, string $mobilePhone, string $series, string $theNumberOfActions, string $numberFrom, string $numberTo, string $nominalValue){

        $this->id = $id;
        $this->entityName = $entityName;
        $this->name = $name;
        $this->countryCode = $countryCode;
        $this->resident = $resident;
        $this->postalCode = $postalCode;
        $this->city = $city;
        $this->street = $street;
        $this->houseNumber = $houseNumber;
        $this->correspondencePostalCode = $correspondencePostalCode;
        $this->correspondenceCity = $correspondenceCity;
        $this->correspondenceStreet = $correspondenceStreet;
        $this->correspondenceHouseNumber = $correspondenceHouseNumber;
        $this->peselRegonRfi = $peselRegonRfi;
        $this->idNumber = $idNumber;
        $this->landlinePhone = $landlinePhone;
        $this->email = $email;
        $this->mobilePhone = $mobilePhone;
        $this->series = $series;
        $this->theNumberOfActions = $theNumberOfActions;
        $this->numberFrom = $numberFrom;
        $this->numberTo = $numberTo;
        $this->nominalValue = $nominalValue;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * @param string $entityName
     */
    public function setEntityName(string $entityName): void
    {
        $this->entityName = $entityName;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCountryCode(): string
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     */
    public function setCountryCode(string $countryCode): void
    {
        $this->countryCode = $countryCode;
    }

    /**
     * @return string
     */
    public function getResident(): string
    {
        return $this->resident;
    }

    /**
     * @param string $resident
     */
    public function setResident(string $resident): void
    {
        $this->resident = $resident;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    /**
     * @param string $postalCode
     */
    public function setPostalCode(string $postalCode): void
    {
        $this->postalCode = $postalCode;
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    /**
     * @return string
     */
    public function getStreet(): string
    {
        return $this->street;
    }

    /**
     * @param string $street
     */
    public function setStreet(string $street): void
    {
        $this->street = $street;
    }

    /**
     * @return string
     */
    public function getHouseNumber(): string
    {
        return $this->houseNumber;
    }

    /**
     * @param string $houseNumber
     */
    public function setHouseNumber(string $houseNumber): void
    {
        $this->houseNumber = $houseNumber;
    }

    /**
     * @return string
     */
    public function getCorrespondencePostalCode(): string
    {
        return $this->correspondencePostalCode;
    }

    /**
     * @param string $correspondencePostalCode
     */
    public function setCorrespondencePostalCode(string $correspondencePostalCode): void
    {
        $this->correspondencePostalCode = $correspondencePostalCode;
    }

    /**
     * @return string
     */
    public function getCorrespondenceCity(): string
    {
        return $this->correspondenceCity;
    }

    /**
     * @param string $correspondenceCity
     */
    public function setCorrespondenceCity(string $correspondenceCity): void
    {
        $this->correspondenceCity = $correspondenceCity;
    }

    /**
     * @return string
     */
    public function getCorrespondenceStreet(): string
    {
        return $this->correspondenceStreet;
    }

    /**
     * @param string $correspondenceStreet
     */
    public function setCorrespondenceStreet(string $correspondenceStreet): void
    {
        $this->correspondenceStreet = $correspondenceStreet;
    }

    /**
     * @return string
     */
    public function getCorrespondenceHouseNumber(): string
    {
        return $this->correspondenceHouseNumber;
    }

    /**
     * @param string $correspondenceHouseNumber
     */
    public function setCorrespondenceHouseNumber(string $correspondenceHouseNumber): void
    {
        $this->correspondenceHouseNumber = $correspondenceHouseNumber;
    }

    /**
     * @return string
     */
    public function getPeselRegonRfi(): string
    {
        return $this->peselRegonRfi;
    }

    /**
     * @param string $peselRegonRfi
     */
    public function setPeselRegonRfi(string $peselRegonRfi): void
    {
        $this->peselRegonRfi = $peselRegonRfi;
    }

    /**
     * @return string
     */
    public function getIdNumber(): string
    {
        return $this->idNumber;
    }

    /**
     * @param string $idNumber
     */
    public function setIdNumber(string $idNumber): void
    {
        $this->idNumber = $idNumber;
    }

    /**
     * @return string
     */
    public function getLandlinePhone(): string
    {
        return $this->landlinePhone;
    }

    /**
     * @param string $landlinePhone
     */
    public function setLandlinePhone(string $landlinePhone): void
    {
        $this->landlinePhone = $landlinePhone;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getMobilePhone(): string
    {
        return $this->mobilePhone;
    }

    /**
     * @param string $mobilePhone
     */
    public function setMobilePhone(string $mobilePhone): void
    {
        $this->mobilePhone = $mobilePhone;
    }

    /**
     * @return string
     */
    public function getSeries(): string
    {
        return $this->series;
    }

    /**
     * @param string $series
     */
    public function setSeries(string $series): void
    {
        $this->series = $series;
    }

    /**
     * @return string
     */
    public function getTheNumberOfActions(): string
    {
        return $this->theNumberOfActions;
    }

    /**
     * @param string $theNumberOfActions
     */
    public function setTheNumberOfActions(string $theNumberOfActions): void
    {
        $this->theNumberOfActions = $theNumberOfActions;
    }

    /**
     * @return string
     */
    public function getNumberFrom(): string
    {
        return $this->numberFrom;
    }

    /**
     * @param string $numberFrom
     */
    public function setNumberFrom(string $numberFrom): void
    {
        $this->numberFrom = $numberFrom;
    }

    /**
     * @return string
     */
    public function getNumberTo(): string
    {
        return $this->numberTo;
    }

    /**
     * @param string $numberTo
     */
    public function setNumberTo(string $numberTo): void
    {
        $this->numberTo = $numberTo;
    }

    /**
     * @return string
     */
    public function getNominalValue(): string
    {
        return $this->nominalValue;
    }

    /**
     * @param string $nominalValue
     */
    public function setNominalValue(string $nominalValue): void
    {
        $this->nominalValue = $nominalValue;
    }



}
