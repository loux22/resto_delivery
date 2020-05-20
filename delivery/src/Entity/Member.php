<?php

namespace App\Entity;

use App\Repository\MemberRepository;
use Symfony\Component\Validator\Constraints as Error;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MemberRepository::class)
 */
class Member
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Error\Length(min=3, max=255, minMessage="ton username '{{ value }}' est trop court", 
     * maxMessage="Ton username '{{ value }}' est trop long")
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255)
     * @Error\Length(min=3, max=30, minMessage="ton lastname '{{ value }}' est trop court", 
     * maxMessage="Ton lastname '{{ value }}' est trop long")
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", length=255)
     * @Error\Length(min=10, max=255, minMessage="ton adresse '{{ value }}' est trop court", 
     * maxMessage="Ton adresse '{{ value }}' est trop long")
     */
    private $address;

    /**
     * @ORM\Column(type="float")
     */
    private $sold;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getSold(): ?float
    {
        return $this->sold;
    }

    public function setSold(float $sold): self
    {
        $this->sold = $sold;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
