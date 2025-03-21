<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Security\AuthCode;

final readonly class AuthCodeGenerator implements AuthCodeGeneratorInterface
{
    public function __construct(
        private int $digits,
    ) {
    }

    public function generateCode(): string
    {
        $min = 10 ** ($this->digits - 1);
        $max = 10 ** $this->digits - 1;

        return (string) random_int($min, $max);
    }
}
