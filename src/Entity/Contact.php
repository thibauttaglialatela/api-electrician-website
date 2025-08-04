<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\SubjectMessage;
use App\Repository\ContactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50, maxMessage: 'Your lastname cannot be longer than {{ limit }} characters')]
    private string $lastname;

    #[ORM\Column(length: 250)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Email(message: 'The email {{ value }} is not a valid email')]
    private string $email;

    #[ORM\Column(enumType: SubjectMessage::class)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private SubjectMessage $subject = SubjectMessage::INFOS;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    private string $message;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    #[Assert\IsTrue(message: 'Please accept the policies')]
    private bool $hasAcceptedPolicies = false;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getSubject(): ?SubjectMessage
    {
        return $this->subject;
    }

    public function setSubject(SubjectMessage $subject): static
    {
        $this->subject = $subject;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

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

    public function hasAcceptedPolicies(): ?bool
    {
        return $this->hasAcceptedPolicies;
    }

    public function setHasAcceptedPolicies(bool $hasAcceptedPolicies): static
    {
        $this->hasAcceptedPolicies = $hasAcceptedPolicies;

        return $this;
    }
}
