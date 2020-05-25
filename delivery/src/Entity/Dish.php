<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DishRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass=DishRepository::class)
 */
class Dish
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
    private $img;

    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $price;

    /**
     * @ORM\ManyToOne(targetEntity=Restorer::class, inversedBy="dishes")
     */
    private $restorer;

    /**
     * @ORM\OneToMany(targetEntity=CommandDish::class, mappedBy="dish")
     */
    private $commandDishes;

    /**
     * @ORM\OneToMany(targetEntity=Note::class, mappedBy="dish")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="dish")
     */
    private $comments;

    public function __construct()
    {
        $this->commandDishes = new ArrayCollection();
        $this->notes = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): self
    {
        $this->img = $img;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getRestorer(): ?Restorer
    {
        return $this->restorer;
    }

    public function setRestorer(?Restorer $restorer): self
    {
        $this->restorer = $restorer;

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
            $commandDish->setDish($this);
        }

        return $this;
    }

    public function removeCommandDish(CommandDish $commandDish): self
    {
        if ($this->commandDishes->contains($commandDish)) {
            $this->commandDishes->removeElement($commandDish);
            // set the owning side to null (unless already changed)
            if ($commandDish->getDish() === $this) {
                $commandDish->setDish(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    public function addNote(Note $note): self
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setDish($this);
        }

        return $this;
    }

    public function removeNote(Note $note): self
    {
        if ($this->notes->contains($note)) {
            $this->notes->removeElement($note);
            // set the owning side to null (unless already changed)
            if ($note->getDish() === $this) {
                $note->setDish(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setDish($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getDish() === $this) {
                $comment->setDish(null);
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


    public function dirFile($user, $id)
    {
        return __DIR__ . '/../../public/dishs/' . $user . '/dish/' . $id;
    }

    public function fileUpload($user, $id)
    {
        if($this -> file  != null){
            $newName = $this -> renameFile($this -> file -> getClientOriginalName());
            $this -> img = $newName;
            $this -> file -> move($this->dirFile($user, $id),$newName);
        }
    }

    public function renameFile($nom)
    {
        return 'img_' . time() . '_' . rand(1,99999) . '_' . $nom;
    }

    public function removeFile($user, $id)
    {
        if(file_exists($this->dirFile($user, $id) . $this-> img) && $this-> img != 'default.jpg'){
            unlink($this->dirFile($user, $id) . $this-> img);
        }
        
    }
}
