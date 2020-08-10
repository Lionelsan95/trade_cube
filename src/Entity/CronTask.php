<?php

namespace App\Entity;

use App\Repository\CronTaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CronTaskRepository::class)
 */
class CronTask
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Wallet")
     */
    private $wallet;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $parametre;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParametre(): ?\DateTimeInterface
    {
        return $this->parametre;
    }

    public function setParametre(?\DateTimeInterface $parametre): self
    {
        $this->parametre = $parametre;

        return $this;
    }

    public function getWallet(): ?Wallet
    {
        return $this->wallet;
    }

    public function setWallet(?Wallet $wallet): self
    {
        $this->wallet = $wallet;

        return $this;
    }
}
