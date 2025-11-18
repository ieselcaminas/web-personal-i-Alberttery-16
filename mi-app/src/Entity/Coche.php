<?php

namespace App\Entity;

use App\Repository\CocheRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CocheRepository::class)]
class Coche
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Marca = null;

    #[ORM\Column(length: 255)]
    private ?string $Modelo = null;

    #[ORM\Column(length: 255)]
    private ?string $Carroceria = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarca(): ?string
    {
        return $this->Marca;
    }

    public function setMarca(string $Marca): static
    {
        $this->Marca = $Marca;

        return $this;
    }

    public function getModelo(): ?string
    {
        return $this->Modelo;
    }

    public function setModelo(string $Modelo): static
    {
        $this->Modelo = $Modelo;

        return $this;
    }

    public function getCarroceria(): ?string
    {
        return $this->Carroceria;
    }

    public function setCarroceria(string $Carroceria): static
    {
        $this->Carroceria = $Carroceria;

        return $this;
    }
}
