<?php
declare(strict_types=1);

namespace App\Message;

abstract class AbstractGenerateEmailMessage
{
    public function __construct(
        protected string $email,
    ) {}

    public function getEmail(): string
    {
        return $this->email;
    }
}
