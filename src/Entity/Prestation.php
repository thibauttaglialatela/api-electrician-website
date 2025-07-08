<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\PrestationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: PrestationRepository::class)]
class Prestation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('prestations:list')]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups('prestations:list')]
    private string $name;

    #[ORM\Column]
    #[Groups('prestations:list')]
    private int $price;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups('prestations:detail')]
    private string $description;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], inversedBy: 'prestation')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    #[Groups('prestations:list')]
    private Image $image;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

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

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(Image $image): static
    {
        $this->image = $image;

        return $this;
    }
}
