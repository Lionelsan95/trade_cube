<?php

namespace App\Entity;

use App\Repository\CryptomonnaieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use ApiPlatform\Core\Annotation\ApiResource;

/**
 * @ORM\Entity(repositoryClass=CryptomonnaieRepository::class)
 *
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"get"={"method"="POST"}},
 *     },
 *     itemOperations={
 *          "get"
 *     }
 * )
 */
class Cryptomonnaie
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=2,
     *     max=20
     * )
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Wallet", mappedBy="cryptomonnaie")
     */
    private $wallets;

    /**
     * @ORM\Column(type="string", length=5)
     * @Assert\Currency()
     */
    private $symbol;

    public function __construct()
    {
        $this->wallets = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getName()." - ".$this->getSymbol();
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

    public function getSymbol(): ?string
    {
        return $this->symbol;
    }

    public function setSymbol(string $symbol): self
    {
        $this->symbol = $symbol;

        return $this;
    }

    /**
     * @return Collection|Wallet[]
     */
    public function getWallets(): Collection
    {
        return $this->wallets;
    }

    public function addWallet(Wallet $wallet): self
    {
        if (!$this->wallets->contains($wallet)) {
            $this->wallets[] = $wallet;
            $wallet->setCryptomonnaie($this);
        }

        return $this;
    }

    public function removeWallet(Wallet $wallet): self
    {
        if ($this->wallets->contains($wallet)) {
            $this->wallets->removeElement($wallet);
            // set the owning side to null (unless already changed)
            if ($wallet->getCryptomonnaie() === $this) {
                $wallet->setCryptomonnaie(null);
            }
        }

        return $this;
    }
}
