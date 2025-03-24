<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Security;

use Scheb\TwoFactorBundle\Security\TwoFactor\AuthenticationContextInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorProviderInterface;
use Symfony\Component\Clock\ClockInterface;
use Umanit\TwoFactorSms\Model\Sms\TwoFactorSmsInterface;
use Umanit\TwoFactorSms\Security\AuthCode\AuthCodeSenderInterface;

final readonly class SmsTwoFactorProvider implements TwoFactorProviderInterface
{
    public function __construct(
        private AuthCodeSenderInterface $authCodeSender,
        private TwoFactorFormRendererInterface $formRenderer,
        private ClockInterface $clock,
    ) {
    }

    public function beginAuthentication(AuthenticationContextInterface $context): bool
    {
        $user = $context->getUser();

        return $user instanceof TwoFactorSmsInterface && $user->isSmsAuthEnabled();
    }

    public function prepareAuthentication(object $user): void
    {
        if (!$user instanceof TwoFactorSmsInterface) {
            return;
        }

        $this->authCodeSender->generateAndSend($user);
    }

    public function validateAuthenticationCode(object $user, string $authenticationCode): bool
    {
        if (!$user instanceof TwoFactorSmsInterface) {
            return false;
        }

        $expiresAt = $user->getSmsAuthCodeExpiresAt();
        if (null !== $expiresAt && $this->clock->now()->getTimestamp() >= $expiresAt->getTimestamp()) {
            $this->authCodeSender->generateAndSend($user);

            return false;
        }

        return $user->getSmsAuthCode() === \str_replace(' ', '', $authenticationCode);
    }

    public function getFormRenderer(): TwoFactorFormRendererInterface
    {
        return $this->formRenderer;
    }
}
