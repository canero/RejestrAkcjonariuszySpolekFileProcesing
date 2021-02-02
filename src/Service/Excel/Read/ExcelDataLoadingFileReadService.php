<?php


namespace App\Service\Excel\Read;


use App\Entity\DataLoadingFileEntity;

use App\Service\Excel\Read\Interfaces\ExcelReadFileServiceInterface;
use Symfony\Component\HttpFoundation\Request;

class ExcelDataLoadingFileReadService
{
    /**
     * @var ExcelReadFileServiceInterface
     */
    private ExcelReadFileServiceInterface $readFileService;

    /**
     * ExcelRasSchemaReadService constructor.
     * @param ExcelReadFileServiceInterface $readFileService
     */
    public function __construct(ExcelReadFileServiceInterface $readFileService)
    {
        $this->readFileService = $readFileService;
    }

    /**
     * Function read file and make array of RasSchema entity
     * @param Request $request
     * @return array<DataLoadingFileEntity>|null
     */
    public function read(Request $request): ?array
    {
        $array = $this->readFileService->read($request);
        if (is_null($array)) {
            return null;
        }
        return $this->makeArrayOfItemsBySheet($array);
    }

    /**
     * @param string $path
     * @return array<DataLoadingFileEntity>|null
     */
    public function readFilePath(string $path): ?array
    {
        $array = $this->readFileService->readPath($path);
        if (is_null($array)) {
            return null;
        }
        return $this->makeArrayOfItemsBySheet($array);
    }

    /**
     * @param array $array
     * @return array
     */
    private function makeArrayOfItemsBySheet(array $array): array
    {
        $arrayOfSheetRasSchema = array();
        foreach ($array as $item){
            if(is_array($item)){
                if(key_exists('value',$item) && key_exists('name',$item)){
                    array_push($arrayOfSheetRasSchema, array('name'=>$item['name'], 'value' => $this->makeArrayOfItems($item['value'])));
                }
            }
        }
        return $arrayOfSheetRasSchema;
    }
    /**

    /**
     * Function make array of ras schema item
     * @param array $array
     * @return array<DataLoadingFileEntity>
     */
    private function makeArrayOfItems(array $array): array
    {
        $arrayOfRasSchema = array();
        foreach ($array as $key => $item) {
            if ($this->checkIfEnd($item, $key) == true) {
                return $arrayOfRasSchema;
            }
            $result = $this->makeRasSchema($item, $key);

            if (!is_null($result)) {
                array_push($arrayOfRasSchema, $result);
            }
        }
        return $arrayOfRasSchema;
    }

    /**
     * function generate ras schema entity
     * @param array|null $item
     * @param int $key
     * @return DataLoadingFileEntity|null
     */
    private function makeRasSchema(?array $item, int $key): ?DataLoadingFileEntity
    {


        if ($key < 4) {
            return null;
        }

        if (strlen($item['B']) == 0) {
            return null;
        }

        return new DataLoadingFileEntity(
            $item['A'],
            $item['B'],
            $item['C'],
            $item['D'],
            $item['E'],
            $item['F'],
            $item['G'],
            $item['H'],
            $item['I'],
            $item['J'],
            $item['K'],
        );
    }


    /**
     * function check if excel is end
     *
     * @param array|null $item
     * @param int $key
     * @return bool
     */
    private function checkIfEnd(?array $item, int $key): bool
    {
        if ($key < 4) {
            return false;
        }

        if (!is_array($item)) {
            return true;
        }

        if (sizeof($item) < 11) {
            return true;
        }

        if (
            strlen($item['A']) == 0
            && strlen($item['B']) == 0
            && strlen($item['C']) == 0
            && strlen($item['D']) == 0
            && strlen($item['E']) == 0
            && strlen($item['F']) == 0
            && strlen($item['G']) == 0
            && strlen($item['H']) == 0
            && strlen($item['I']) == 0
            && strlen($item['J']) == 0
            && strlen($item['K']) == 0
        ) {
            return true;
        }

        return false;

    }
}
