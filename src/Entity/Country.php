<?php

namespace App\Entity;

use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=HidingPlace::class, mappedBy="country")
     */
    private $hidingPlaces;

    public function __construct()
    {
        $this->hidingPlaces = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|HidingPlace[]
     */
    public function getHidingPlaces(): Collection
    {
        return $this->hidingPlaces;
    }

    public function addHidingPlace(HidingPlace $hidingPlace): self
    {
        if (!$this->hidingPlaces->contains($hidingPlace)) {
            $this->hidingPlaces[] = $hidingPlace;
            $hidingPlace->setCountry($this);
        }

        return $this;
    }

    public function removeHidingPlace(HidingPlace $hidingPlace): self
    {
        if ($this->hidingPlaces->removeElement($hidingPlace)) {
            // set the owning side to null (unless already changed)
            if ($hidingPlace->getCountry() === $this) {
                $hidingPlace->setCountry(null);
            }
        }

        return $this;
    }
}
