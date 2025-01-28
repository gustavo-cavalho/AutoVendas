<?php

namespace App\Entity;

use App\Repository\CarStoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CarStoreRepository::class)
 */
class CarStore
{
    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $credencial;

    /**
     * @ORM\OneToMany(targetEntity=user::class, mappedBy="carStore")
     */
    private $employers;

    /**
     * @ORM\OneToMany(targetEntity=Vehicle::class, mappedBy="carStore")
     */
    private $vehicleStock;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="advertiserStore", orphanRemoval=true)
     */
    private $ads;

    public function __construct()
    {
        $this->employers = new ArrayCollection();
        $this->vehicleStock = new ArrayCollection();
        $this->ads = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCredencial(): ?string
    {
        return $this->credencial;
    }

    public function setCredencial(string $credencial): self
    {
        $this->credencial = $credencial;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getEmployers(): Collection
    {
        return $this->employers;
    }

    public function addEmployer(user $employer): self
    {
        if (!$this->employers->contains($employer)) {
            $this->employers[] = $employer;
            $employer->setCarStore($this);
        }

        return $this;
    }

    public function removeEmployer(user $employer): self
    {
        if ($this->employers->removeElement($employer)) {
            // set the owning side to null (unless already changed)
            if ($employer->getCarStore() === $this) {
                $employer->setCarStore(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicleStock(): Collection
    {
        return $this->vehicleStock;
    }

    public function addVehicleStock(Vehicle $vehicleStock): self
    {
        if (!$this->vehicleStock->contains($vehicleStock)) {
            $this->vehicleStock[] = $vehicleStock;
            $vehicleStock->setCarStore($this);
        }

        return $this;
    }

    public function removeVehicleStock(Vehicle $vehicleStock): self
    {
        if ($this->vehicleStock->removeElement($vehicleStock)) {
            // set the owning side to null (unless already changed)
            if ($vehicleStock->getCarStore() === $this) {
                $vehicleStock->setCarStore(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Ad>
     */
    public function getAds(): Collection
    {
        return $this->ads;
    }

    public function addAd(Ad $ad): self
    {
        if (!$this->ads->contains($ad)) {
            $this->ads[] = $ad;
            $ad->setAdvertiserStore($this);
        }

        return $this;
    }

    public function removeAd(Ad $ad): self
    {
        if ($this->ads->removeElement($ad)) {
            // set the owning side to null (unless already changed)
            if ($ad->getAdvertiserStore() === $this) {
                $ad->setAdvertiserStore(null);
            }
        }

        return $this;
    }
}
