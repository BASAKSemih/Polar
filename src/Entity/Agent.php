<?php

namespace App\Entity;

use App\Repository\AgentRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AgentRepository::class)
 */
class Agent
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
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\ManyToMany(targetEntity=Speciality::class, inversedBy="agents")
     */
    private $speciality;

    /**
     * @ORM\ManyToMany(targetEntity=Nationality::class, inversedBy="agents")
     */
    private $nationality;

    /**
     * @ORM\Column(type="text")
     */
    private $biography;

    /**
     * @ORM\ManyToMany(targetEntity=Mission::class, mappedBy="agent")
     */
    private $missions;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->speciality = new ArrayCollection();
        $this->nationality = new ArrayCollection();
        $this->missions = new ArrayCollection();
    }

    public function setSpeciality(ArrayCollection $speciality): void
    {
        $this->speciality = $speciality;
    }

    public function setNationality(ArrayCollection $nationality): void
    {
        $this->nationality = $nationality;
    }

    public function setMissions(ArrayCollection $missions): void
    {
        $this->missions = $missions;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTimeInterface $birthDate): self
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * @return Collection|Speciality[]
     */
    public function getSpeciality(): Collection
    {
        return $this->speciality;
    }

    public function addSpeciality(Speciality $speciality): self
    {
        if (!$this->speciality->contains($speciality)) {
            $this->speciality[] = $speciality;
        }

        return $this;
    }

    public function removeSpeciality(Speciality $speciality): self
    {
        $this->speciality->removeElement($speciality);

        return $this;
    }

    /**
     * @return Collection|Nationality[]
     */
    public function getNationality(): Collection
    {
        return $this->nationality;
    }

    public function addNationality(Nationality $nationality): self
    {
        if (!$this->nationality->contains($nationality)) {
            $this->nationality[] = $nationality;
        }

        return $this;
    }

    public function removeNationality(Nationality $nationality): self
    {
        $this->nationality->removeElement($nationality);

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): self
    {
        $this->biography = $biography;

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
            $mission->addAgent($this);
        }

        return $this;
    }

    public function removeMission(Mission $mission): self
    {
        if ($this->missions->removeElement($mission)) {
            $mission->removeAgent($this);
        }

        return $this;
    }
}
