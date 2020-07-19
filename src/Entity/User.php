<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @package App\Entity
 */
class User extends BaseUser{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $iban;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $codepin;

    /**
     * @ORM\OneToMany(targetEntity="UserChain", mappedBy="user")
     */
    private $blockchains;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $date_naiss;

    public function __construct()
    {
        parent::__construct();
        $this->blockchains = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): self
    {
        $this->iban = $iban;

        return $this;
    }

    public function getCodepin(): ?string
    {
        return $this->codepin;
    }

    public function setCodepin(string $codepin): self
    {
        $this->codepin = $codepin;

        return $this;
    }

    public function getDateNaiss(): ?\DateTimeInterface
    {
        return $this->date_naiss;
    }

    public function setDateNaiss(?\DateTimeInterface $date_naiss): self
    {
        $this->date_naiss = $date_naiss;

        return $this;
    }

    /**
     * @return Collection|UserChain[]
     */
    public function getBlockchains(): Collection
    {
        return $this->blockchains;
    }

    public function addBlockchain(UserChain $blockchain): self
    {
        if (!$this->blockchains->contains($blockchain)) {
            $this->blockchains[] = $blockchain;
            $blockchain->setUser($this);
        }

        return $this;
    }

    public function removeBlockchain(UserChain $blockchain): self
    {
        if ($this->blockchains->contains($blockchain)) {
            $this->blockchains->removeElement($blockchain);
            // set the owning side to null (unless already changed)
            if ($blockchain->getUser() === $this) {
                $blockchain->setUser(null);
            }
        }

        return $this;
    }
}
