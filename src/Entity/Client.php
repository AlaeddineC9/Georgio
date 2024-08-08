<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
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

    /**
     * @var Collection<int, contact>
     */
    #[ORM\OneToMany(targetEntity: Contact::class, mappedBy: 'client')]
    private Collection $contact;

    /**
     * @var Collection<int, booking>
     */
    #[ORM\OneToMany(targetEntity: Booking::class, mappedBy: 'client')]
    private Collection $booking;

    /**
     * @var Collection<int, booking>
     */
    #[ORM\OneToMany(targetEntity: Galerie::class, mappedBy: 'client')]
    private Collection $galerie;

    /**
     * @var Collection<int, service>
     */
    #[ORM\ManyToMany(targetEntity: Service::class, inversedBy: 'clients')]
    private Collection $service;

    public function __construct()
    {
        $this->contact = new ArrayCollection();
        $this->booking = new ArrayCollection();
        $this->galerie = new ArrayCollection();
        $this->service = new ArrayCollection();
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

    /**
     * @return Collection<int, contact>
     */
    public function getContact(): Collection
    {
        return $this->contact;
    }

    public function addContact(contact $contact): static
    {
        if (!$this->contact->contains($contact)) {
            $this->contact->add($contact);
            $contact->setClient($this);
        }

        return $this;
    }

    public function removeContact(contact $contact): static
    {
        if ($this->contact->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getClient() === $this) {
                $contact->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, booking>
     */
    public function getBooking(): Collection
    {
        return $this->booking;
    }

    public function addBooking(booking $booking): static
    {
        if (!$this->booking->contains($booking)) {
            $this->booking->add($booking);
            $booking->setClient($this);
        }

        return $this;
    }

    public function removeBooking(booking $booking): static
    {
        if ($this->booking->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getClient() === $this) {
                $booking->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, booking>
     */
    public function getGalerie(): Collection
    {
        return $this->galerie;
    }

    public function addGalerie(booking $galerie): static
    {
        if (!$this->galerie->contains($galerie)) {
            $this->galerie->add($galerie);
            $galerie->setClient($this);
        }

        return $this;
    }

    public function removeGalerie(booking $galerie): static
    {
        if ($this->galerie->removeElement($galerie)) {
            // set the owning side to null (unless already changed)
            if ($galerie->getClient() === $this) {
                $galerie->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, service>
     */
    public function getService(): Collection
    {
        return $this->service;
    }

    public function addService(service $service): static
    {
        if (!$this->service->contains($service)) {
            $this->service->add($service);
        }

        return $this;
    }

    public function removeService(service $service): static
    {
        $this->service->removeElement($service);

        return $this;
    }
}
