<?php

namespace App\Entity;

use App\Repository\SalesReportRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalesReportRepository::class)]
class SalesReport
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column]
    private ?int $totalOrders = 0;

    #[ORM\Column]
    private ?float $totalRevenue = 0;

    #[ORM\Column]
    private ?int $totalProducts = 0;

    #[ORM\Column(nullable: true)]
    private ?float $averageOrderValue = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $topProducts = [];

    #[ORM\Column(type: Types::JSON, nullable: true)]
    private array $categoryDistribution = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getTotalOrders(): ?int
    {
        return $this->totalOrders;
    }

    public function setTotalOrders(int $totalOrders): static
    {
        $this->totalOrders = $totalOrders;
        return $this;
    }

    public function getTotalRevenue(): ?float
    {
        return $this->totalRevenue;
    }

    public function setTotalRevenue(float $totalRevenue): static
    {
        $this->totalRevenue = $totalRevenue;
        return $this;
    }

    public function getTotalProducts(): ?int
    {
        return $this->totalProducts;
    }

    public function setTotalProducts(int $totalProducts): static
    {
        $this->totalProducts = $totalProducts;
        return $this;
    }

    public function getAverageOrderValue(): ?float
    {
        return $this->averageOrderValue;
    }

    public function setAverageOrderValue(?float $averageOrderValue): static
    {
        $this->averageOrderValue = $averageOrderValue;
        return $this;
    }

    public function getTopProducts(): array
    {
        return $this->topProducts;
    }

    public function setTopProducts(?array $topProducts): static
    {
        $this->topProducts = $topProducts;
        return $this;
    }

    public function getCategoryDistribution(): array
    {
        return $this->categoryDistribution;
    }

    public function setCategoryDistribution(?array $categoryDistribution): static
    {
        $this->categoryDistribution = $categoryDistribution;
        return $this;
    }
} 