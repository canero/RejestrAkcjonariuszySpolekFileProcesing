<?php


namespace App\Entity;


class DataLoadingFileEntity
{
    private string $id;
    private string $status;
    private string $entityName;
    private string $name;
    private string $peselRegonRfi;
    private string $theNumberOfActions;
    private string $nominalValue;
    private string $numberFrom;
    private string $numberTo;
    private string $bankAccount;
    private string $brokerageAccount;

    /**
     * DataLoadingFileEntity constructor.
     * @param string $id
     * @param string $status
     * @param string $entityName
     * @param string $name
     * @param string $peselRegonRfi
     * @param string $theNumberOfActions
     * @param string $nominalValue
     * @param string $numberFrom
     * @param string $numberTo
     * @param string $bankAccount
     * @param string $brokerageAccount
     */
    public function __construct(string $id, string $status, string $entityName, string $name, string $peselRegonRfi, string $theNumberOfActions, string $nominalValue, string $numberFrom, string $numberTo, string $bankAccount, string $brokerageAccount)
    {
        $this->id = $id;
        $this->status = $status;
        $this->entityName = $entityName;
        $this->name = $name;
        $this->peselRegonRfi = $peselRegonRfi;
        $this->theNumberOfActions = $theNumberOfActions;
        $this->nominalValue = $nominalValue;
        $this->numberFrom = $numberFrom;
        $this->numberTo = $numberTo;
        $this->bankAccount = $bankAccount;
        $this->brokerageAccount = $brokerageAccount;
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
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
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
    public function getBankAccount(): string
    {
        return $this->bankAccount;
    }

    /**
     * @param string $bankAccount
     */
    public function setBankAccount(string $bankAccount): void
    {
        $this->bankAccount = $bankAccount;
    }

    /**
     * @return string
     */
    public function getBrokerageAccount(): string
    {
        return $this->brokerageAccount;
    }

    /**
     * @param string $brokerageAccount
     */
    public function setBrokerageAccount(string $brokerageAccount): void
    {
        $this->brokerageAccount = $brokerageAccount;
    }


}
