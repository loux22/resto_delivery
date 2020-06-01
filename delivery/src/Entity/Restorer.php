<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RestorerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=RestorerRepository::class)
 */
class Restorer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $logo = 'default.jpg';

    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $address;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Dish::class, mappedBy="restorer")
     */
    private $dishes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $category;

    public function __construct()
    {
        $this->dishes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Dish[]
     */
    public function getDishes(): Collection
    {
        return $this->dishes;
    }

    public function addDish(Dish $dish): self
    {
        if (!$this->dishes->contains($dish)) {
            $this->dishes[] = $dish;
            $dish->setRestorer($this);
        }

        return $this;
    }

    public function removeDish(Dish $dish): self
    {
        if ($this->dishes->contains($dish)) {
            $this->dishes->removeElement($dish);
            // set the owning side to null (unless already changed)
            if ($dish->getRestorer() === $this) {
                $dish->setRestorer(null);
            }
        }

        return $this;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile(UploadedFile $file)
    {
        $this -> file = $file;
        return $this;
    }


    public function dirFile($id)
    {
        return __DIR__ . '/../../public/restorer/' . $id . '/logo';
    }

    public function fileUpload($id)
    {
        if($this -> file  != null){
            $newName = $this -> renameFile($this -> file -> getClientOriginalName());
            $this -> logo = $newName;
            $this -> file -> move($this->dirFile($id),$newName);
        }
    }

    public function renameFile($nom)
    {
        return 'logo_' . time() . '_' . rand(1,99999) . '_' . $nom;
    }

    public function removeFile($id)
    {
        if(file_exists($this->dirFile($id) . $this-> logo) && $this-> logo != 'default.jpg'){
            unlink($this->dirFile($id) . $this-> logo);
        }
        
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): self
    {
        $this->category = $category;

        return $this;
    }
}
