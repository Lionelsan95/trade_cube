<?php

namespace App\Entity;

use App\Repository\BlockchainRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=BlockchainRepository::class)
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
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min=2,
     *     max=50
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=10,
     *     max=100
     * )
     */
    private $cle_api;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Wallet", mappedBy="blockchain")
     */
    private $wallets;

    public function __construct()
    {
        $this->wallets = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCleApi(): ?string
    {
        return $this->cle_api;
    }

    public function setCleApi(string $cle_api): self
    {
        $this->cle_api = $cle_api;

        return $this;
    }

    public function postUpdate(){

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
            $wallet->setBlockchain($this);
        }

        return $this;
    }

    public function removeWallet(Wallet $wallet): self
    {
        if ($this->wallets->contains($wallet)) {
            $this->wallets->removeElement($wallet);
            // set the owning side to null (unless already changed)
            if ($wallet->getBlockchain() === $this) {
                $wallet->setBlockchain(null);
            }
        }

        return $this;
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
}
