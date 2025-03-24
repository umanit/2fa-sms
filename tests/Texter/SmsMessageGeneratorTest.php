<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Tests\Texter;

use PHPUnit\Framework\TestCase;
use Umanit\TwoFactorSms\Model\Sms\TwoFactorSmsInterface;
use Umanit\TwoFactorSms\Texter\SmsMessageGenerator;

class SmsMessageGeneratorTest extends TestCase
{
    private const RECIPIENT_MOBILE = '+33623456789';
    private const MESSAGE = 'Your OTP code: [[auth_code]]';
    private const AUTH_CODE = '123456';

    private SmsMessageGenerator $messageGenerator;

    protected function setUp(): void
    {
        $this->messageGenerator = new SmsMessageGenerator(self::MESSAGE);
    }

    public function testThatSmsHasCustomValues(): void
    {
        $user = $this->createMock(TwoFactorSmsInterface::class);
        $user
            ->expects($this->once())
            ->method('getSmsAuthRecipient')
            ->willReturn(self::RECIPIENT_MOBILE)
        ;

        $user
            ->expects($this->once())
            ->method('getSmsAuthCode')
            ->willReturn(self::AUTH_CODE)
        ;

        $message = $this->messageGenerator->generateMessage($user);

        $this->assertEquals(self::RECIPIENT_MOBILE, $message->getPhone());
        $this->assertEquals('Your OTP code: ' . self::AUTH_CODE, $message->getSubject());
    }

    public function testInvalidArgumentExceptionWhenUserHasNoAuthCode(): void
    {
        $user = $this->createMock(TwoFactorSmsInterface::class);
        $user
            ->expects($this->once())
            ->method('getSmsAuthCode')
            ->willReturn(null)
        ;

        $this->expectException(\InvalidArgumentException::class);

        $this->messageGenerator->generateMessage($user);
    }
}
