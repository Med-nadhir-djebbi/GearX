<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::OBJECT)]
    private ?object $category = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $ratings = [];

    #[ORM\Column]
    private ?int $price = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategory(): ?object
    {
        return $this->category;
    }

    public function setCategory(object $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getRatings(): array
    {
        return $this->ratings;
    }

    public function setRatings(array $ratings): static
    {
        $this->ratings = $ratings;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }
}
