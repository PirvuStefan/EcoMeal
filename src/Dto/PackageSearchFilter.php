<?php

namespace App\Dto;

use App\Entity\Category;

class PackageSearchFilter
{

    public ?string $name = null;

    public ?float $minPrice = null;

    public ?float $maxPrice = null;

    public ?Category $category = null;



}
