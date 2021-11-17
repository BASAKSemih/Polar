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

    /**
     * @ORM\OneToMany(targetEntity=Mission::class, mappedBy="country")
     */
    private $missions;

    /**
     * @ORM\OneToOne(targetEntity=Nationality::class, mappedBy="country", cascade={"persist", "remove"})
     */
    private $nationality;

    public function __construct()
    {
        $this->hidingPlaces = new ArrayCollection();
        $this->missions = new ArrayCollection();
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

    /**
     * @return Collection|Mission[]
     */
    public function getMissions(): Collection
    {
        return $this->missions;
    }

    public function addMission(Mission $mission): self
    {
        if (!$this->missions->contains($mission)) {
            $this->missions[] = $mission;
            $mission->setCountry($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            // set the owning side to null (unless already changed)
            if ($mission->getCountry() === $this) {
                $mission->setCountry(null);
            }
        }

        return $this;
    }

    public function getNationality(): ?Nationality
    {
        return $this->nationality;
    }

    public function setNationality(Nationality $nationality): self
    {
        // set the owning side of the relation if necessary
        if ($nationality->getCountry() !== $this) {
            $nationality->setCountry($this);
        }

        $this->nationality = $nationality;

        return $this;
    }
}
