<?php

namespace App\Entity;

use App\Repository\WalletRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\WalletRepository", repositoryClass=WalletRepository::class)
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
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=3,
     *     max=50
     * )
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=10,
     *     max=50
     * )
     * @Assert\Unique()
     */
    private $signature;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=10,
     *     max=50
     * )
     * @Assert\Unique()
     */
    private $apikey;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     * @Assert\Length(
     *     min=10,
     *     max=50
     * )
     * @Assert\Unique()
     */
    private $secretkey;

    /**
     * @ORM\Column(type="float")
     * @Assert\Positive()
     */
    private $sill;

    /**
     * @ORM\Column(type="float")
     * @Assert\Positive
     */
    private $tranche;

    /**
     * @ORM\Column(type="integer")
     * @Assert\GreaterThanOrEqual(
     *     value=0
     * )
     * @Assert\LessThanOrEqual(
     *     value= 1
     * )
     */
    private $trading;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Cryptomonnaie", inversedBy="wallets")
     * @ORM\JoinColumn(nullable=false)
     */
    private $currency;

    /**
     * @ORM\Column(type="float")
     * @Assert\GreaterThanOrEqual(
     *     value=0
     * )
     */
    private $balance;

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
        $this->setBalance(0);
        $this->setTrading("0");
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTranche(): ?float
    {
        return $this->tranche;
    }

    public function setTranche(float $tranche): self
    {
        $this->tranche = $tranche;

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

    public function getTrading(): ?int
    {
        return ($this->trading==1)?1:0;
    }

    public function setTrading(int $trading): self
    {
        $this->trading = $trading;

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

    public function getApikey(): ?string
    {
        return $this->apikey;
    }

    public function setApikey(string $apikey): self
    {
        $this->apikey = $apikey;

        return $this;
    }

    public function getSecretkey(): ?string
    {
        return $this->secretkey;
    }

    public function setSecretkey(string $secretkey): self
    {
        $this->secretkey = $secretkey;

        return $this;
    }

    public function getSill(): ?float
    {
        return $this->sill;
    }

    public function setSill(float $sill): self
    {
        $this->sill = $sill;

        return $this;
    }

    public function getBalance(): ?float
    {
        return $this->balance;
    }

    public function setBalance(float $balance): self
    {
        $this->balance = $balance;

        return $this;
    }

    public function getCurrency(): ?Cryptomonnaie
    {
        return $this->currency;
    }

    public function setCurrency(?Cryptomonnaie $currency): self
    {
        $this->currency = $currency;

        return $this;
    }
}
