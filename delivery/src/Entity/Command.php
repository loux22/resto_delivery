<?php

namespace App\Entity;

use App\Repository\CommandRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommandRepository::class)
 */
class Command
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="commands")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $delivery;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="boolean")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity=CommandDish::class, mappedBy="command")
     */
    private $commandDishes;

    public function __construct()
    {
        $this->commandDishes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDelivery(): ?\DateTimeInterface
    {
        return $this->delivery;
    }

    public function setDelivery(\DateTimeInterface $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection|CommandDish[]
     */
    public function getCommandDishes(): Collection
    {
        return $this->commandDishes;
    }

    public function addCommandDish(CommandDish $commandDish): self
    {
        if (!$this->commandDishes->contains($commandDish)) {
            $this->commandDishes[] = $commandDish;
            $commandDish->setCommand($this);
        }

        return $this;
    }

    public function removeCommandDish(CommandDish $commandDish): self
    {
        if ($this->commandDishes->contains($commandDish)) {
            $this->commandDishes->removeElement($commandDish);
            // set the owning side to null (unless already changed)
            if ($commandDish->getCommand() === $this) {
                $commandDish->setCommand(null);
            }
        }

        return $this;
    }
}
