<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Tests\Texter;

use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Umanit\TwoFactorSms\Model\Sms\TwoFactorSmsInterface;
use Umanit\TwoFactorSms\Texter\NotifierAuthCodeTexter;
use Umanit\TwoFactorSms\Texter\SmsMessageGeneratorInterface;

class NotifierAuthCodeTexterTest extends WebTestCase
{
    private MockObject&TexterInterface $texter;
    private MockObject&SmsMessageGeneratorInterface $messageGenerator;
    private NotifierAuthCodeTexter $authCodeTexter;

    protected function setUp(): void
    {
        $this->texter = $this->createMock(TexterInterface::class);
        $this->messageGenerator = $this->createMock(SmsMessageGeneratorInterface::class);

        $this->authCodeTexter = new NotifierAuthCodeTexter($this->texter, $this->messageGenerator);
    }

    public function testThatEmailIsSent(): void
    {
        $user = $this->createMock(TwoFactorSmsInterface::class);

        $user
            ->expects($this->once())
            ->method('getSmsAuthCode')
            ->willReturn('123456')
        ;

        $this->messageGenerator
            ->expects($this->once())
            ->method('generateMessage')
            ->with($user)
            ->willReturn($this->createMock(SmsMessage::class))
        ;

        $this->texter
            ->expects($this->once())
            ->method('send')
        ;

        $this->authCodeTexter->sendAuthCode($user);
    }

    public function testThatNullAuthCodeSendsNoEmail(): void
    {
        $user = $this->createMock(TwoFactorSmsInterface::class);

        $user
            ->expects($this->any())
            ->method('getSmsAuthCode')
            ->willReturn(null)
        ;

        $this->messageGenerator
            ->expects($this->never())
            ->method('generateMessage')
        ;

        $this->texter
            ->expects($this->never())
            ->method('send')
        ;

        $this->authCodeTexter->sendAuthCode($user);
    }
}
