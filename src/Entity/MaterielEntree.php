<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MaterielEntreeRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MaterielEntreeRepository::class)
 */
class MaterielEntree
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\Length(min=14,minMessage="La quantité minimum est 0")
     */
    private $qteEntree;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateEntree;

    /**
     * @ORM\ManyToOne(targetEntity=Materiel::class, inversedBy="materielEntrees")
     * @ORM\JoinColumn(nullable=false)
     */
    private $materiel;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQteEntree(): ?int
    {
        return $this->qteEntree;
    }

    public function setQteEntree(int $qteEntree): self
    {
        $this->qteEntree = $qteEntree;

        return $this;
    }

    public function getDateEntree(): ?\DateTimeInterface
    {
        return $this->dateEntree;
    }

    public function setDateEntree(\DateTimeInterface $dateEntree): self
    {
        $this->dateEntree = $dateEntree;

        return $this;
    }

    public function getMateriel(): ?Materiel
    {
        return $this->materiel;
    }

    public function setMateriel(?Materiel $materiel): self
    {
        $this->materiel = $materiel;

        return $this;
    }
}
