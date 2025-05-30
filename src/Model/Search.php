<?php
namespace App\Model;

/**
 * Permet de créer un formulaire de recherche grace à un SearchType
 */
class Search
{
    /**
     * @var string
     */
    private ?string $string = '';

    /**
     * @var array
     */
    private array $categories = [];

    private ?float $minPrice = null;
    private ?float $maxPrice = null;
    private ?string $availability = null;
    private ?string $sortBy = 'name';
    private ?string $sortOrder = 'asc';

    public function getString(): ?string
    {
        return $this->string;
    }

    /**
    * @return  self
    */ 
    public function setString(?string $string): self
    {
        $this->string = $string;

        return $this;
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    /**

     * @return  self
    */ 
    public function setCategories(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    public function setMinPrice(?float $minPrice): self
    {
        $this->minPrice = $minPrice;
        return $this;
    }

    public function getMaxPrice(): ?float
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(?float $maxPrice): self
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }

    public function getAvailability(): ?string
    {
        return $this->availability;
    }

    public function setAvailability(?string $availability): self
    {
        $this->availability = $availability;
        return $this;
    }

    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }

    public function setSortBy(?string $sortBy): self
    {
        $this->sortBy = $sortBy;
        return $this;
    }

    public function getSortOrder(): ?string
    {
        return $this->sortOrder;
    }

    public function setSortOrder(?string $sortOrder): self
    {
        $this->sortOrder = $sortOrder;
        return $this;
    }
}