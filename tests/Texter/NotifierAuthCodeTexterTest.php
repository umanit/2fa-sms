<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Tests\Texter;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Umanit\TwoFactorSms\Model\Sms\TwoFactorSmsInterface;
use Umanit\TwoFactorSms\Texter\NotifierAuthCodeTexter;

class NotifierAuthCodeTexterTest extends WebTestCase
{
    private const RECIPIENT_MOBILE = '+33623456789';
    private const MESSAGE = 'Your OTP code: [[auth_code]]';
    private const AUTH_CODE = '123456';

    public function testSendSMS(): void
    {
        $texter = $this->createMock(TexterInterface::class);

        $user = $this->createMock(TwoFactorSmsInterface::class);
        $user->method('getSmsAuthCode')->willReturn(self::AUTH_CODE);
        $user->method('getSmsAuthRecipient')->willReturn(self::RECIPIENT_MOBILE);

        $expectedSms = new SmsMessage('+33623456789', 'Your OTP code: 123456');
        $texter
            ->expects($this->once())
            ->method('send')
            ->with(
                $this->callback(function (SmsMessage $sms) use ($expectedSms) {
                    return $sms->getPhone() === $expectedSms->getPhone()
                        && $sms->getSubject() === $expectedSms->getSubject();
                })
            )
        ;

        $authCodeTexter = new NotifierAuthCodeTexter($texter, self::MESSAGE);
        $authCodeTexter->sendAuthCode($user);
    }

    public function testNoSendWhenUserHasNoAuthCode(): void
    {
        $texter = $this->createMock(TexterInterface::class);
        $texter->expects($this->never())->method('send');

        $authCodeTexter = new NotifierAuthCodeTexter($texter, self::MESSAGE);

        $user = $this->createMock(TwoFactorSmsInterface::class);
        $user
            ->expects($this->once())
            ->method('getSmsAuthCode')
            ->willReturn(null)
        ;

        $authCodeTexter->sendAuthCode($user);
    }
}
