<?php


namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class UserChain
 * @ORM\Entity
 * @ORM\Table(name="user_chain")
 * @package App\Entity
 */
class UserChain
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $seuil;// va etre definit dans un wallet #2449943 => 300€ mais le sueil sera de 100€ par ex
    // wallet = (
    // id,
    // nom,
    // signature,
    // crytomonnaie (id -> cryto (-> name (bitcoin), btc))
    // somme )

    /**
     * @ORM\Column(type="float")
     */
    private $tranche;

    /**
     * @ORM\Column(type="string")
     */
    private $cle;

    /**
     * @ORM\Column(type="integer")
     */
    private $active;

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

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCle(): ?string
    {
        return $this->cle;
    }

    public function setCle(string $cle): self
    {
        $this->cle = $cle;

        return $this;
    }

    public function getActive(): ?int
    {
        return $this->active;
    }

    public function setActive(int $active): self
    {
        $this->active = $active;

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
