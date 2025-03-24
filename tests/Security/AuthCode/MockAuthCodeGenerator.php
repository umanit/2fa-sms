<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Tests\Security\AuthCode;

use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeGeneratorInterface;

final readonly class MockAuthCodeGenerator implements AuthCodeGeneratorInterface
{
    public function __construct(
        private string $authCode,
    ) {
    }

    public function generateCode(): string
    {
        return $this->authCode;
    }
}
