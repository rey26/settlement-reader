<?php

namespace App\Entity;

use App\Repository\SettlementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SettlementRepository::class)]
#[ORM\Index(['name'])]
class Settlement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $mayorName = null;

    #[ORM\Column(length: 255)]
    private ?string $cityHallAddress = null;

    #[ORM\Column(length: 130, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $fax = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coatOfArmsPath = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childSettlements')]
    private ?self $parent = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: self::class)]
    private Collection $childSettlements;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $webAddress = null;

    public function __construct()
    {
        $this->childSettlements = new ArrayCollection();
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

    public function getMayorName(): ?string
    {
        return $this->mayorName;
    }

    public function setMayorName(string $mayorName): static
    {
        $this->mayorName = $mayorName;

        return $this;
    }

    public function getCityHallAddress(): ?string
    {
        return $this->cityHallAddress;
    }

    public function setCityHallAddress(string $cityHallAddress): static
    {
        $this->cityHallAddress = $cityHallAddress;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): static
    {
        $this->fax = $fax;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getCoatOfArmsPath(): ?string
    {
        return $this->coatOfArmsPath;
    }

    public function setCoatOfArmsPath(?string $coatOfArmsPath): static
    {
        $this->coatOfArmsPath = $coatOfArmsPath;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): static
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildSettlements(): Collection
    {
        return $this->childSettlements;
    }

    public function addChildSettlement(self $childSettlement): static
    {
        if (!$this->childSettlements->contains($childSettlement)) {
            $this->childSettlements->add($childSettlement);
            $childSettlement->setParent($this);
        }

        return $this;
    }

    public function removeChildSettlement(self $childSettlement): static
    {
        if ($this->childSettlements->removeElement($childSettlement)) {
            // set the owning side to null (unless already changed)
            if ($childSettlement->getParent() === $this) {
                $childSettlement->setParent(null);
            }
        }

        return $this;
    }

    public function getWebAddress(): ?string
    {
        return $this->webAddress;
    }

    public function setWebAddress(?string $webAddress): static
    {
        $this->webAddress = $webAddress;

        return $this;
    }
}
