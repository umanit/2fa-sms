<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Texter;

use Symfony\Component\Notifier\Message\SmsMessage;
use Umanit\TwoFactorSms\Model\Sms\TwoFactorSmsInterface;

final readonly class SmsMessageGenerator implements SmsMessageGeneratorInterface
{
    public function __construct(
        private string $message,
    ) {
    }

    public function generateMessage(TwoFactorSmsInterface $user): SmsMessage
    {
        $authCode = $user->getSmsAuthCode();
        if (null === $authCode) {
            throw new \InvalidArgumentException('A SMS auth code is required to generate a message.');
        }

        return new SmsMessage(
            $user->getSmsAuthRecipient(),
            str_replace('[[auth_code]]', $authCode, $this->message),
        );
    }
}
