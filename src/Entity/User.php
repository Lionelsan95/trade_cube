<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class User
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @package App\Entity
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
class User extends BaseUser{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *     min=2,
     *     max=100
     * )
     */
    private $name;

    /**
     *
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(
     *     min=2,
     *     max=100
     * )
     */
    private $surname;

    /**
     * @Groups("user")
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Assert\Length(
     *     min=6,
     *     max=20
     * )
     */
    private $phonenumber;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Assert\Iban()
     */
    private $iban;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $codepin;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Wallet", mappedBy="user")
     */
    private $wallets;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date()
     */
    private $birthdate;

    public function __construct()
    {
        parent::__construct();
        $this->blockchains = new ArrayCollection();
        $this->wallets = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setCodepin(?string $codepin): self
    {
        $this->codepin = $codepin;

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
            $wallet->setUser($this);
        }

        return $this;
    }

    public function removeWallet(Wallet $wallet): self
    {
        if ($this->wallets->contains($wallet)) {
            $this->wallets->removeElement($wallet);
            // set the owning side to null (unless already changed)
            if ($wallet->getUser() === $this) {
                $wallet->setUser(null);
            }
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): self
    {
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(?\DateTimeInterface $birthdate): self
    {
        $this->birthdate = $birthdate;

        return $this;
    }
}
