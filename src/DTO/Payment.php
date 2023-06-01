<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Address;

class Payment
{
   public ?string $cardNumber = '';
    public ?string $cardName ='';
    public ?string $cvc = '';
    public ?string $expirationDate='';
    public ?Address $address= null;
}
