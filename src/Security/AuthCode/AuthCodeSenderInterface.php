<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Security\AuthCode;

use Umanit\TwoFactorSms\Model\Sms\TwoFactorSmsInterface;

interface AuthCodeSenderInterface
{
    /**
     * Generate the authentication code and send it to the user.
     */
    public function generateAndSend(TwoFactorSmsInterface $user): void;
}
