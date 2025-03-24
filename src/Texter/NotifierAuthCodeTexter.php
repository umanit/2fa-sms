<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Texter;

use Symfony\Component\Notifier\TexterInterface;
use Umanit\TwoFactorSms\Model\Sms\TwoFactorSmsInterface;

final readonly class NotifierAuthCodeTexter implements AuthCodeTexterInterface
{
    public function __construct(
        private TexterInterface $texter,
        private SmsMessageGeneratorInterface $messageGenerator,
    ) {
    }

    public function sendAuthCode(TwoFactorSmsInterface $user): void
    {
        $authCode = $user->getSmsAuthCode();
        if (null === $authCode) {
            return;
        }

        $this->texter->send($this->messageGenerator->generateMessage($user));
    }
}
