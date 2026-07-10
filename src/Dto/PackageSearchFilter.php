<?php

namespace App\Dto;

use App\Entity\Business;
use App\Entity\BusinessType;
use App\Entity\Category;

class PackageSearchFilter
{

    public ?string $name = null;

    public ?float $minPrice = null;

    public ?float $maxPrice = null;

    public ?Category $category = null;

    public ?Business $business = null;

    public ?BusinessType $businessType = null;

}
