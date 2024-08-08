<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var Collection<int, contact>
     */
    #[ORM\OneToMany(targetEntity: contact::class, mappedBy: 'user')]
    private Collection $contact;

    /**
     * @var Collection<int, booking>
     */
    #[ORM\OneToMany(targetEntity: booking::class, mappedBy: 'user')]
    private Collection $booking;

    /**
     * @var Collection<int, galerie>
     */
    #[ORM\OneToMany(targetEntity: galerie::class, mappedBy: 'user')]
    private Collection $galerie;

    /**
     * @var Collection<int, service>
     */
    #[ORM\OneToMany(targetEntity: service::class, mappedBy: 'user')]
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
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $contact->setUser($this);
        }

        return $this;
    }

    public function removeContact(contact $contact): static
    {
        if ($this->contact->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getUser() === $this) {
                $contact->setUser(null);
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
            $booking->setUser($this);
        }

        return $this;
    }

    public function removeBooking(booking $booking): static
    {
        if ($this->booking->removeElement($booking)) {
            // set the owning side to null (unless already changed)
            if ($booking->getUser() === $this) {
                $booking->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, galerie>
     */
    public function getGalerie(): Collection
    {
        return $this->galerie;
    }

    public function addGalerie(galerie $galerie): static
    {
        if (!$this->galerie->contains($galerie)) {
            $this->galerie->add($galerie);
            $galerie->setUser($this);
        }

        return $this;
    }

    public function removeGalerie(galerie $galerie): static
    {
        if ($this->galerie->removeElement($galerie)) {
            // set the owning side to null (unless already changed)
            if ($galerie->getUser() === $this) {
                $galerie->setUser(null);
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
            $service->setUser($this);
        }

        return $this;
    }

    public function removeService(service $service): static
    {
        if ($this->service->removeElement($service)) {
            // set the owning side to null (unless already changed)
            if ($service->getUser() === $this) {
                $service->setUser(null);
            }
        }

        return $this;
    }
}
