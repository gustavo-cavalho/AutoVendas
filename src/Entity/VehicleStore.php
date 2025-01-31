<?php

namespace App\Entity;

use App\Repository\VehicleStoreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=VehicleStoreRepository::class)
 */
class VehicleStore
{
    public const SERIALIZE_SHOW = 'show_store';
    public const SERIALIZE_INDEX = 'index_store';

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_DISABLE = 'DISABLE';

    /**
     * @Groups({"show_store", "index_store"})
     *
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     *
     * @Groups({"show_store"})
     */
    private $credencial;

    /**
     * @ORM\OneToMany(targetEntity=Vehicle::class, mappedBy="vehicleStore")
     *
     * @Groups({"show_store", "admin_store"})
     */
    private $vehicleStock;

    /**
     * @ORM\OneToMany(targetEntity=user::class, mappedBy="vehicleStore")
     *
     * @Groups({"show_store", "admin_store"})
     */
    private $employers;

    /**
     * @ORM\OneToMany(targetEntity=Ad::class, mappedBy="advertiserStore", orphanRemoval=true)
     *
     * @Groups({"show_store", "admin_store"})
     */
    private $ads;

    /**
     * @ORM\Column(type="string", length=15)
     *
     * @Groups({"show_store"})
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"show_store"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @Groups({"show_store", "index_store"})
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity=Address::class, mappedBy="vehicleStore", cascade={"persist", "remove"})
     *
     * @Groups({"show_store", "index_store"})
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=20)
     *
     * @Groups({"show_store"})
     */
    private $status = self::STATUS_ACTIVE;

    public function __construct()
    {
        $this->employers = new ArrayCollection();
        $this->vehicleStock = new ArrayCollection();
        $this->ads = new ArrayCollection();
        $this->status = self::STATUS_ACTIVE;
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
            $employer->setVehicleStore($this);
        }

        return $this;
    }

    public function removeEmployer(user $employer): self
    {
        if ($this->employers->removeElement($employer)) {
            // set the owning side to null (unless already changed)
            if ($employer->getVehicleStore() === $this) {
                $employer->setVehicleStore(null);
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
            $vehicleStock->setVehicleStore($this);
        }

        return $this;
    }

    public function removeVehicleStock(Vehicle $vehicleStock): self
    {
        if ($this->vehicleStock->removeElement($vehicleStock)) {
            // set the owning side to null (unless already changed)
            if ($vehicleStock->getVehicleStore() === $this) {
                $vehicleStock->setVehicleStore(null);
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

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
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

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(Address $address): self
    {
        // set the owning side of the relation if necessary
        if ($address->getVehicleStore() !== $this) {
            $address->setVehicleStore($this);
        }

        $this->address = $address;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
