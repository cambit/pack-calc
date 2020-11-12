<?php

namespace App\Entity;

use App\Repository\CalculationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CalculationRepository::class)
 */
class Calculation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private ?string $hash;

    /**
     * @ORM\Column(type="json")
     */
    private array $products = [];

    /**
     * @ORM\Column(type="json")
     */
    private array $box = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $alert;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHash(): ?string
    {
        return $this->hash;
    }

    /**
     * @return $this
     */
    public function setHash(string $hash): self
    {
        $this->hash = $hash;

        return $this;
    }

    public function getProducts(): ?array
    {
        return $this->products;
    }

    /**
     * @return $this
     */
    public function setProducts(array $products): self
    {
        $this->products = $products;

        return $this;
    }

    public function getBox(): ?array
    {
        return $this->box;
    }

    /**
     * @return $this
     */
    public function setBox(array $box): self
    {
        $this->box = $box;

        return $this;
    }

    public function getAlert(): ?bool
    {
        return $this->alert;
    }

    /**
     * @return $this
     */
    public function setAlert(?bool $alert): self
    {
        $this->alert = $alert;

        return $this;
    }
}
