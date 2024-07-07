<?php
declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;


class UpdateClient
{
    public function __construct(
        #[Assert\Length(min: 2, minMessage: 'First name must be longer than {{ limit }}characters')]
        public ?string $firstName,

        #[Assert\Length(min: 2, minMessage: 'Last name must be longer than {{ limit }}characters')]
        public ?string $lastName,

        #[Assert\Email]
        public ?string $email,
    )
    {

    }

}