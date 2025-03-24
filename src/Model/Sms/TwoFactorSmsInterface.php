<?php

declare(strict_types=1);

namespace Umanit\TwoFactorSms\Model\Sms;

interface TwoFactorSmsInterface
{
    /**
     * Return true if the user should do two-factor authentication.
     */
    public function isSmsAuthEnabled(): bool;

    /**
     * Return user mobile phone number.
     */
    public function getSmsAuthRecipient(): string;

    /**
     * Return the authentication code.
     */
    public function getSmsAuthCode(): ?string;

    /**
     * Set the authentication code.
     */
    public function setSmsAuthCode(?string $authCode): void;

    /**
     * Get the expiration date of the authentication code.
     */
    public function getSmsAuthCodeExpiresAt(): ?\DateTimeImmutable;

    /**
     * Set the expiration date of the authentication code.
     */
    public function setSmsAuthCodeExpiresAt(?\DateTimeImmutable $expiresAt): void;
}
