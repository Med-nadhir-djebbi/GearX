<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class CategoryExtension extends AbstractExtension implements GlobalsInterface
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function getGlobals(): array
    {
        return [
            'global_categories' => $this->categoryRepository->findAll(),
        ];
    }
} 