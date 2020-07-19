<?php

namespace App\Entity;

use App\Repository\BlockchainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BlockchainRepository::class)
 */
class Blockchain
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name; // blockchain.com

    //créer une autre entité nommé wallet

    /**
     * @ORM\OneToMany(targetEntity="UserChain", mappedBy="blockchain")
     */
    private $users;

    public function __construct()
    {
        parent::__construct();
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection|UserChain[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(UserChain $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setBlockchain($this);
        }

        return $this;
    }

    public function removeUser(UserChain $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getBlockchain() === $this) {
                $user->setBlockchain(null);
            }
        }

        return $this;
    }
}
