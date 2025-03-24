<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Texter;

use Symfony\Component\Notifier\Message\SmsMessage;
use Umanit\TwoFactorSms\Model\Sms\TwoFactorSmsInterface;

interface SmsMessageGeneratorInterface
{
    public function generateMessage(TwoFactorSmsInterface $user): SmsMessage;
}
