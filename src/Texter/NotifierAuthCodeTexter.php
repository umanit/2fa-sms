<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Texter;

use Symfony\Component\Notifier\Message\SmsMessage;
use Symfony\Component\Notifier\TexterInterface;
use Umanit\TwoFactorSms\Model\Sms\TwoFactorSmsInterface;

final readonly class NotifierAuthCodeTexter implements AuthCodeTexterInterface
{
    public function __construct(
        private TexterInterface $texter,
        private string $message,
    ) {
    }

    public function sendAuthCode(TwoFactorSmsInterface $user): void
    {
        $authCode = $user->getSmsAuthCode();
        if (null === $authCode) {
            return;
        }

        $sms = new SmsMessage(
            $user->getSmsAuthRecipient(),
            $this->getMessage($authCode),
        );

        $this->texter->send($sms);
    }

    public function getMessage(string $authCode): string
    {
        return str_replace('[[auth_code]]', $authCode, $this->message);
    }
}
