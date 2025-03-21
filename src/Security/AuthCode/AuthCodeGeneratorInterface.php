<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Security\AuthCode;

interface AuthCodeGeneratorInterface
{
    /**
     * Generate the authentication code.
     */
    public function generateCode(): string;
}
