<?php

namespace App\Entity;

use App\Repository\CouponRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CouponRepository::class)]
class Coupon
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20, unique: true)]
    private ?string $code = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $discountAmount = null;

    #[ORM\Column]
    private ?bool $isPercentage = true;

    #[ORM\Column]
    private ?float $minimumSpend = 0;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $validFrom = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $validUntil = null;

    #[ORM\Column]
    private ?int $usageLimit = null;

    #[ORM\Column]
    private ?int $usageCount = 0;

    #[ORM\Column]
    private ?bool $isActive = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getDiscountAmount(): ?float
    {
        return $this->discountAmount;
    }

    public function setDiscountAmount(float $discountAmount): static
    {
        $this->discountAmount = $discountAmount;
        return $this;
    }

    public function isIsPercentage(): ?bool
    {
        return $this->isPercentage;
    }

    public function setIsPercentage(bool $isPercentage): static
    {
        $this->isPercentage = $isPercentage;
        return $this;
    }

    public function getMinimumSpend(): ?float
    {
        return $this->minimumSpend;
    }

    public function setMinimumSpend(float $minimumSpend): static
    {
        $this->minimumSpend = $minimumSpend;
        return $this;
    }

    public function getValidFrom(): ?\DateTimeInterface
    {
        return $this->validFrom;
    }

    public function setValidFrom(\DateTimeInterface $validFrom): static
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    public function getValidUntil(): ?\DateTimeInterface
    {
        return $this->validUntil;
    }

    public function setValidUntil(?\DateTimeInterface $validUntil): static
    {
        $this->validUntil = $validUntil;
        return $this;
    }

    public function getUsageLimit(): ?int
    {
        return $this->usageLimit;
    }

    public function setUsageLimit(int $usageLimit): static
    {
        $this->usageLimit = $usageLimit;
        return $this;
    }

    public function getUsageCount(): ?int
    {
        return $this->usageCount;
    }

    public function setUsageCount(int $usageCount): static
    {
        $this->usageCount = $usageCount;
        return $this;
    }

    public function isIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function isValid(): bool
    {
        $now = new \DateTime();
        
        if (!$this->isActive) {
            return false;
        }

        if ($this->usageLimit > 0 && $this->usageCount >= $this->usageLimit) {
            return false;
        }

        if ($this->validFrom > $now) {
            return false;
        }

        if ($this->validUntil !== null && $this->validUntil < $now) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        if (!$this->isValid() || $amount < $this->minimumSpend) {
            return 0;
        }

        if ($this->isPercentage) {
            return $amount * ($this->discountAmount / 100);
        }

        return min($this->discountAmount, $amount);
    }
} 