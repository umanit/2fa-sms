<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Tests\Security\AuthCode;

use PHPUnit\Framework\TestCase;
use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeGenerator;

class AuthCodeGeneratorTest extends TestCase
{
    private const DIGITS = 6;

    public function testGenerateCodeLength(): void
    {
        $codeGenerator = new AuthCodeGenerator(self::DIGITS);

        $this->assertSame(self::DIGITS, mb_strlen($codeGenerator->generateCode()));
    }

    public function testGenerateCodeIsRandom(): void
    {
        $codeGenerator = new AuthCodeGenerator(self::DIGITS);

        $this->assertNotEquals($codeGenerator->generateCode(), $codeGenerator->generateCode());
    }
}
