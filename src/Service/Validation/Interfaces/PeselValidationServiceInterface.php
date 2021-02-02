<?php

namespace App\Service\Validation\Interfaces;

interface PeselValidationServiceInterface
{
    public function verify(string $pesel): bool;

    public function returnResponse(): array;
}
