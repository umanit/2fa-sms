<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Tests\Security\AuthCode;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Scheb\TwoFactorBundle\Model\PersisterInterface;
use Symfony\Component\Clock\MockClock;
use Umanit\TwoFactorSms\Model\Sms\TwoFactorSmsInterface;
use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeSender;
use Umanit\TwoFactorSms\Texter\AuthCodeTexterInterface;

class AuthCodeSenderTest extends TestCase
{
    private const AUTH_CODE = '123456';

    private MockAuthCodeGenerator $authCodeGenerator;
    private MockObject&PersisterInterface $persister;
    private MockObject&AuthCodeTexterInterface $texter;
    private MockClock $clock;
    private AuthCodeSender $authCodeSender;

    protected function setUp(): void
    {
        $this->persister = $this->createMock(PersisterInterface::class);
        $this->texter = $this->createMock(AuthCodeTexterInterface::class);
        $this->clock = new MockClock();
        $this->authCodeGenerator = new MockAuthCodeGenerator(self::AUTH_CODE);

        $this->authCodeSender = new AuthCodeSender(
            $this->authCodeGenerator,
            $this->persister,
            $this->texter,
            $this->clock,
        );
    }

    public function testThatAuthCodeIsSetOnUser(): void
    {
        $user = $this->createMock(TwoFactorSmsInterface::class);
        $user->expects($this->once())
             ->method('setSmsAuthCode')
             ->with(self::AUTH_CODE)
        ;

        $this->authCodeSender->generateAndSend($user);
    }

    public function testThatAuthCodeIsPersisted(): void
    {
        $user = $this->createMock(TwoFactorSmsInterface::class);

        $this->persister
            ->expects($this->once())
            ->method('persist')
            ->with($user)
        ;

        $this->authCodeSender->generateAndSend($user);
    }

    public function testThatAuthCodeIsSendViaSms(): void
    {
        $user = $this->createMock(TwoFactorSmsInterface::class);

        $this->texter
            ->expects($this->once())
            ->method('sendAuthCode')
            ->with($user)
        ;

        $this->authCodeSender->generateAndSend($user);
    }

    public function testThatNoExpirationIsSetOnUser(): void
    {
        $user = $this->createMock(TwoFactorSmsInterface::class);
        $user->expects($this->never())
             ->method('setSmsAuthCodeExpiresAt')
        ;

        $this->authCodeSender->generateAndSend($user);
    }

    public function testThatExpirationIsSetOnUser(): void
    {
        $user = $this->createMock(TwoFactorSmsInterface::class);
        $user->expects($this->once())
             ->method('setSmsAuthCodeExpiresAt')
             ->with($this->clock->now()->add(new \DateInterval('PT5M')))
        ;

        $authCodeSender = new AuthCodeSender(
            $this->authCodeGenerator,
            $this->persister,
            $this->texter,
            $this->clock,
            'PT5M'
        );
        $authCodeSender->generateAndSend($user);
    }
}
