<?php

namespace App\Entity;

use App\Repository\BookingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookingRepository::class)]
class Booking
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    private ?string $name = null;

    #[ORM\Column(length: 30)]
    private ?string $phone_number = null;

    #[ORM\Column(length: 80)]
    private ?string $email = null;

    #[ORM\Column]
    private ?int $nb_guest = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isAccepted = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $canBook = true; // Ce champ contrôle si les réservations sont possibles

    // #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    // private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\NotBlank(message: "La date est obligatoire.")]
    #[Assert\GreaterThan('today', message: "La date de réservation doit être dans le futur.")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $special_request = null;

    #[ORM\ManyToOne(inversedBy: 'booking')]
    private ?Client $client = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $isVerified = null;



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

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(string $phone_number): static
    {
        $this->phone_number = $phone_number;

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

    public function getNbGuest(): ?int
    {
        return $this->nb_guest;
    }

    public function setNbGuest(int $nb_guest): static
    {
        $this->nb_guest = $nb_guest;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function isAccepted(): ?bool
{
    return $this->isAccepted;
}

public function setIsAccepted(bool $isAccepted): static
{
    $this->isAccepted = $isAccepted;

    return $this;
}

public function canBook(): ?bool
{
    return $this->canBook;
}

public function setCanBook(bool $canBook): static
{
    $this->canBook = $canBook;

    return $this;
}

    public function getSpecialRequest(): ?string
    {
        return $this->special_request;
    }

    public function setSpecialRequest(string $special_request): static
    {
        $this->special_request = $special_request;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }

    public function isVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function __toString(): string
    {
        return $this->phone_number;
    }


}
