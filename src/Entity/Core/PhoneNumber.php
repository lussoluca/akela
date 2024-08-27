<?php

declare(strict_types=1);

namespace App\Entity\Core;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class PhoneNumber implements PhoneNumberInterface, \Stringable
{
    public const CODE_LENGTH = 6;
    #[Column(name: 'number', type: 'string', length: 16)]
    protected string $number;

    #[Column(name: 'country', type: 'string', length: 2)]
    protected string $country;

    #[Column(name: 'verification_code', type: 'string', length: PhoneNumber::CODE_LENGTH, nullable: true)]
    protected string $verificationCode;

    #[Column(name: 'verified', type: 'boolean')]
    protected bool $verified = false;

    /**
     * PhoneNumber constructor.
     */
    public function __construct(string $country, string $number)
    {
        $this->country = $country;
        $this->number = $number;
        $this->verificationCode = $this->generatePhoneCode();
    }

    public function __toString(): string
    {
        return $this->getFullNumber();
    }

    public function getNumber(): string
    {
        return $this->number;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function getVerificationCode(): string
    {
        return $this->verificationCode;
    }

    public function verified(): self
    {
        $new_phone_number = clone $this;
        $new_phone_number->verified = true;
        $new_phone_number->verificationCode = '';

        return $new_phone_number;
    }

    public function isVerified(): bool
    {
        return $this->verified;
    }

    public function getCountryPrefix(): int
    {
        $map = [
            'IT' => 39,
        ];

        return $map[$this->getCountry()];
    }

    public function getFullNumber(): string
    {
        return '+'.$this->getCountryPrefix().$this->getNumber();
    }

    public function equal(PhoneNumber $other): bool
    {
        return $this->number == $other->number && $this->country == $other->country;
    }

    protected function generatePhoneCode(): string
    {
        $digits = 5;

        return str_pad(
            (string) rand(0, pow(10, $digits) - 1),
            $digits,
            '0',
            STR_PAD_LEFT
        );
    }
}
