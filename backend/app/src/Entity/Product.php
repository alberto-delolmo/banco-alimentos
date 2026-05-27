<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $measurementUnit = null;

    #[ORM\Column]
    private ?int $actualStock = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getMeasurementUnit(): ?string
    {
        return $this->measurementUnit;
    }

    public function setMeasurementUnit(string $measurementUnit): static
    {
        $this->measurementUnit = $measurementUnit;

        return $this;
    }

    public function getActualStock(): ?int
    {
        return $this->actualStock;
    }

    public function setActualStock(int $actualStock): static
    {
        $this->actualStock = $actualStock;

        return $this;
    }
}
