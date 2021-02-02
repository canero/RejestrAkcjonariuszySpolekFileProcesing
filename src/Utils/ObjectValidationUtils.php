<?php

namespace App\Utils;

use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ObjectValidationUtils
{

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ValidatorInterface $validator, LoggerInterface $logger)
    {
        $this->validator = $validator;
        $this->logger = $logger;
    }

    public function validate($object, string $class, ?array $array = null):string{
        if(is_null($array)){
            return $this->validateWithoutGroups($object,$class);
        }
        return $this->validateWithGroups($object,$class,$array);
    }

    /**
     * Validate user if are errors retrun string wtih errors adn log it in to file
     *
     * @param $object
     * @return string "" or string with errors
     */
    private function validateWithoutGroups($object, string $class): string
    {
        $errors = $this->validator->validate($object);
        if (count($errors) > 0) {
            $errorString = "";
            foreach ($errors as $violation) {
                $this->logger->error($class . " - " . (string) $violation);
                $errorString = $errorString . $violation->getMessage() . "; ";
            }
            return $errorString;
        }
        return "";
    }

    /**
     * Validate with group user if are errors retrun string wtih errors adn log it in to file
     *
     * @param $object
     * @return string "" or string with errors
     */
    private function validateWithGroups($object, string $class, array $array): string
    {
        $errors = $this->validator->validate($object, null, $array);
        if (count($errors) > 0) {
            $errorString = "";
            foreach ($errors as $violation) {
                $this->logger->error($class . " - " . (string) $violation);
                $errorString = $errorString . $violation->getMessage() . "; ";
            }
            return $errorString;
        }
        return "";
    }

}
