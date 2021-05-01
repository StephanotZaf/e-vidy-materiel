<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank(message="Veuillez remplir votre nom")
     * @Assert\Length(max=70,maxMessage="Le nom est au maximum 70 caractères")
     */
    private $nomCli;

    /**
     * @ORM\Column(type="string", length=70)
     * @Assert\NotBlank(message="Veuillez remplir votre prenom")
     * @Assert\Length(max=70,maxMessage="Le prenom est au maximum 70 caractères")
     */
    private $prenomCli;

    /**
     * @ORM\Column(type="string", length=14, nullable=true)
     * @Assert\Length(max=14,maxMessage="Le numéro de télephone est au maximux 14 chiffres")
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\Length(max=100,maxMessage="L'addresse est au maximux 100 caractères")
     */
    private $addresseCli;

    /**
     * @ORM\ManyToOne(targetEntity=Materiel::class, inversedBy="clients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $materiel;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Length(min=0,minMessage="La quantité est au minimum 0")
     */
    private $qteAchete;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=25)
     */
    private $code;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateAchat;

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\Length(max=15,maxMessage="Le type de paiement est au maximux 15 caractères")
     */
    private $typePaiement;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\Length(min=14,minMessage="L'avance est au minimum 1")
     */
    private $avance;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Assert\Date
     * @var string A "Y-m-d" formatted value
     */
    private $dateDecheance;

    /**
     * @ORM\PrePersist
    */
    public function Prepersist()
    {   
        if(empty($this->dateAchat)){
            $this->dateAchat = new \DateTime();
        }
    }

    public function __toString(){
        return $this->dateAchat;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullName()
    {
       return "{$this->getNomCli()} {$this->getPrenomCli()}";
    }

    public function getNomCli(): ?string
    {
        return $this->nomCli;
    }

    public function setNomCli(string $nomCli): self
    {
        $this->nomCli = $nomCli;

        return $this;
    }

    public function getPrenomCli(): ?string
    {
        return $this->prenomCli;
    }

    public function setPrenomCli(string $prenomCli): self
    {
        $this->prenomCli = $prenomCli;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getAddresseCli(): ?string
    {
        return $this->addresseCli;
    }

    public function setAddresseCli(string $addresseCli): self
    {
        $this->addresseCli = $addresseCli;

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

    public function getQteAchete(): ?int
    {
        return $this->qteAchete;
    }

    public function setQteAchete(int $qteAchete): self
    {
        $this->qteAchete = $qteAchete;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDateAchat(): ?\DateTimeInterface
    {
        return $this->dateAchat;
    }

    public function setDateAchat(\DateTimeInterface $dateAchat): self
    {
        $this->dateAchat = $dateAchat;

        return $this;
    }

    public function getTypePaiement(): ?string
    {
        return $this->typePaiement;
    }

    public function setTypePaiement(string $typePaiement): self
    {
        $this->typePaiement = $typePaiement;

        return $this;
    }

    public function getAvance(): ?int
    {
        return $this->avance;
    }

    public function setAvance(?int $avance): self
    {
        $this->avance = $avance;

        return $this;
    }

    public function getDateDecheance()
    {
        return $this->dateDecheance;
    }

    public function setDateDecheance(?\DateTimeInterface $dateDecheance): self
    {
        $this->dateDecheance = $dateDecheance;

        return $this;
    }
}
