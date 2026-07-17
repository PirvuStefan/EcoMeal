<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Repository\PackageRepository;
use Doctrine\ORM\Mapping as ORM;

#[AllowDynamicProperties]
#[ORM\Entity(repositoryClass: PackageRepository::class)]
class Package
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



    #[ORM\Column(length: 100)]
    private ?string $description = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\ManyToOne(inversedBy: 'packages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Business $business = null;

    #[ORM\OneToOne(mappedBy: 'package', cascade: ['persist', 'remove'])]
    private ?Order $consumer_order = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?float $decrement = null;

    #[ORM\Column(nullable: true)]
    private ?float $discounted_price = null;

    #[ORM\Column(length: 40, nullable: true)]
    private ?string $status = 'available';

    public function getId(): ?int
    {
        return $this->id;
    }



    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getBusiness(): ?Business
    {
        return $this->business;
    }

    public function setBusiness(?Business $business): static
    {
        $this->business = $business;

        return $this;
    }

    public function getConsumerOrder(): ?Order
    {
        return $this->consumer_order;
    }

    public function setConsumerOrder(Order $consumer_order): static
    {
        // set the owning side of the relation if necessary
        if ($consumer_order->getPackage() !== $this) {
            $consumer_order->setPackage($this);
        }

        $this->consumer_order = $consumer_order;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDecrement(): ?float
    {
        return $this->decrement;
    }

    public function setDecrement(float $decrement): static
    {
        $this->decrement = $decrement;

        return $this;
    }

    public function getDiscountedPrice(): ?float
    {
        return $this->discounted_price;
    }

    public function setDiscountedPrice(?float $discounted_price): static
    {
        $this->discounted_price = $discounted_price;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
