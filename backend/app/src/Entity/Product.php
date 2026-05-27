<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: Types::GUID, unique: true)]
    #[Assert\NotBlank(message: 'El id del producto es obligatorio.')]
    #[Assert\Uuid(message: 'El id del producto debe de tener formato UUID.')]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\notBlank(message: 'El nombre del producto es obligatorio.')]
    #[Assert\Length(max: 255, maxMessage: "El nombre del producto no puede tener más de 255 carácteres.")]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Assert\Length(max: 1000, maxMessage: "La descripción del producto no puede tener más de 1000 carácteres.")]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Assert\notBlank(message: 'La unidad de medida del producto es obligatoria.')]
    #[Assert\Length(max: 255, maxMessage: "La unidad de medida del producto no puede tener más de 50 carácteres.")]
    private ?string $measurementUnit = null;

    #[ORM\Column]
    #[Assert\NotNull(message: 'El stock actual es obligatorio.')]
    #[Assert\PositiveOrZero(message: 'El stock actual no puede ser negativo.')]
    private ?int $actualStock = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): static
    {
        $this->id = $id;

        return $this;
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

    public function setActualStock(?int $actualStock): static
    {
        $this->actualStock = $actualStock;

        return $this;
    }
}
