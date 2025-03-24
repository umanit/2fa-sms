<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Texter;

use Umanit\TwoFactorSms\Model\Sms\TwoFactorSmsInterface;

interface AuthCodeTexterInterface
{
    /**
     * Send the authentication code to the user.
     */
    public function sendAuthCode(TwoFactorSmsInterface $user): void;
}
