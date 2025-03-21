<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Texter;

use Umanit\TwoFactorSms\Model\Sms\TwoFactorInterface;

interface AuthCodeTexterInterface
{
    /**
     * Send the authentication code to the user.
     */
    public function sendAuthCode(TwoFactorInterface $user): void;
}
