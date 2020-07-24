<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WalletRepository", repositoryClass=WalletRepository::class)
 */
class Wallet
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
    private $nom;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $signature;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $cle_prive;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $cle_public;

    /**
     * @ORM\Column(type="float")
     */
    private $seuil;

    /**
     * @ORM\Column(type="float")
     */
    private $tranche;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cryptomonnaie", inversedBy="wallets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cryptomonnaie;

    /**
     * @ORM\Column(type="float")
     */
    private $solde;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Blockchain")
     * @ORM\JoinColumn(nullable=false)
     */
    private $blockchain;

    public function __construct()
    {
        $this->solde = 0;
    }

    public function __toString()
    {
        return $this->getNom();
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

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(string $signature): self
    {
        $this->signature = $signature;

        return $this;
    }

    public function getClePrive(): ?string
    {
        return $this->cle_prive;
    }

    public function setClePrive(string $cle_prive): self
    {
        $this->cle_prive = $cle_prive;

        return $this;
    }

    public function getClePublic(): ?string
    {
        return $this->cle_public;
    }

    public function setClePublic(string $cle_public): self
    {
        $this->cle_public = $cle_public;

        return $this;
    }

    public function getSeuil(): ?float
    {
        return $this->seuil;
    }

    public function setSeuil(float $seuil): self
    {
        $this->seuil = $seuil;

        return $this;
    }

    public function getTranche(): ?float
    {
        return $this->tranche;
    }

    public function setTranche(float $tranche): self
    {
        $this->tranche = $tranche;

        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getCryptomonnaie(): ?Cryptomonnaie
    {
        return $this->cryptomonnaie;
    }

    public function setCryptomonnaie(?Cryptomonnaie $cryptomonnaie): self
    {
        $this->cryptomonnaie = $cryptomonnaie;

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

    public function getBlockchain(): ?Blockchain
    {
        return $this->blockchain;
    }

    public function setBlockchain(?Blockchain $blockchain): self
    {
        $this->blockchain = $blockchain;

        return $this;
    }
}
