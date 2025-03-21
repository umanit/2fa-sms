<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Security\AuthCode;

use Scheb\TwoFactorBundle\Model\PersisterInterface;
use Symfony\Component\Clock\ClockInterface;
use Umanit\TwoFactorSms\Model\Sms\TwoFactorInterface;
use Umanit\TwoFactorSms\Texter\AuthCodeTexterInterface;

final readonly class AuthCodeSender implements AuthCodeSenderInterface
{
    public function __construct(
        private AuthCodeGeneratorInterface $authCodeGenerator,
        private PersisterInterface $persister,
        private AuthCodeTexterInterface $texter,
        private ClockInterface $clock,
        private ?string $expiresAfter = null,
    ) {
    }

    public function generateAndSend(TwoFactorInterface $user): void
    {
        $user->setSmsAuthCode($this->authCodeGenerator->generateCode());

        if (null !== $this->expiresAfter) {
            $user->setSmsAuthCodeExpiresAt($this->clock->now()->add(new \DateInterval($this->expiresAfter)));
        }

        $this->persister->persist($user);
        $this->texter->sendAuthCode($user);
    }
}
