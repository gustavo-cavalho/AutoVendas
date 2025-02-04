<?php

namespace App\Entity;

use App\Repository\AdRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * @ORM\Entity(repositoryClass=AdRepository::class)
 */
class Ad
{
    public const SERIALIZE_SHOW = 'ad:show';
    public const SERIALIZE_INDEX = 'ad:index';

    /**
     * @ORM\Id
     *
     * @ORM\GeneratedValue
     *
     * @ORM\Column(type="integer")
     *
     * @Groups({"ad:show", "ad:index"})
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Vehicle::class, inversedBy="ad", cascade={"persist", "remove"})
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"ad:show", "ad:index"})
     *
     * @MaxDepth(1)
     */
    private $vehicleAdvertised;

    /**
     * @ORM\ManyToOne(targetEntity=VehicleStore::class, inversedBy="ads")
     *
     * @ORM\JoinColumn(nullable=false)
     *
     * @Groups({"ad:show"})
     *
     * @MaxDepth(1)
     */
    private $advertiserStore;

    /**
     * @ORM\Column(type="text", nullable=true)
     *
     * @Groups({"ad:show"})
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     *
     * @Groups({"ad:show", "ad:index"})
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @Groups({"ad:show", "ad:index"})
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable")
     *
     * @Groups({"ad:show", "ad:index"})
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVehicleAdvertised(): ?Vehicle
    {
        return $this->vehicleAdvertised;
    }

    public function setVehicleAdvertised(Vehicle $vehicleAdvertised): self
    {
        $this->vehicleAdvertised = $vehicleAdvertised;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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

    public function getAdvertiserStore(): ?VehicleStore
    {
        return $this->advertiserStore;
    }

    public function setAdvertiserStore(?VehicleStore $advertiserStore): self
    {
        $this->advertiserStore = $advertiserStore;

        return $this;
    }
}
