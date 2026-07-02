<?php

namespace App\Entity;

use App\Repository\BussinessTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BussinessTypeRepository::class)]
class BusinessType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, Business>
     */
    #[ORM\OneToMany(targetEntity: Business::class, mappedBy: 'business_type')]
    private Collection $businesses;

    public function __construct()
    {
        $this->businesses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Business>
     */
    public function getBusinesses(): Collection
    {
        return $this->businesses;
    }

    public function addBusiness(Business $business): static
    {
        if (!$this->businesses->contains($business)) {
            $this->businesses->add($business);
            $business->setBusinessType($this);
        }

        return $this;
    }

    public function removeBusiness(Business $business): static
    {
        if ($this->businesses->removeElement($business)) {
            // set the owning side to null (unless already changed)
            if ($business->getBusinessType() === $this) {
                $business->setBusinessType(null);
            }
        }

        return $this;
    }
}
