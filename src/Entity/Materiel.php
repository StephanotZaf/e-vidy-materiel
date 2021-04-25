<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MaterielRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=MaterielRepository::class)
 * @ApiResource(
 *  formats={"json"},
 *  normalizationContext= {"groups"={"materiel_read"}},
 *  collectionOperations={"GET"},
 *  itemOperations={"GET"}
 * ),
 * @UniqueEntity(fields={"designation"},message="Ce détail matériel existe déjà")
 */
class Materiel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"materiel_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Groups({"materiel_read"})
     * @Assert\Length(min=0,minMessage="La quantité minimum est 0")
     */
    private $qte;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"materiel_read"})
     */
    private $dateApprovisionnement;

    /**
     * @ORM\Column(type="string", length=80)
     * @Groups({"materiel_read"})
     * @Assert\NotBlank(message="Le désignation est obligatoire")
     */
    private $designation;

    /**
     * @ORM\Column(type="float")
     * @Groups({"materiel_read"})
     *  @Assert\Length(min=0,minMessage="La quantité minimum est 0")
     */
    private $prixUnitaire;

    /**
     * @ORM\OneToMany(targetEntity=MaterielEntree::class, mappedBy="materiel")
     */
    private $materielEntrees;


    /**
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="materiel")
     */
    private $clients;

    /**
     * @ORM\ManyToOne(targetEntity=Departement::class, inversedBy="materiels")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"materiel_read"})
     */
    private $departement;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"materiel_read"})
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="materiels")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Ce champs est obligatoire")
     * @Groups({"materiel_read"})
     */
    private $categorie;

    public function __construct()
    {
        $this->materielEntrees = new ArrayCollection();
        $this->clients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    // public function getTotalMateriel()
    // {
    //     $qte = ($this->qte) != 0 ? $this->qte : 0;
    //     $qteEntree = array_reduce($this->materielEntrees->toArray(),function($total,$me)
    //     {
    //         return $total + ($me->getQteEntree() != "0" ? $me->getQteEntree() : 0);
    //     },0);
    //     $qteSortie = array_reduce($this->materielSorties->toArray(),function($total,$ms)
    //     {
    //         return $total + ($ms->getQteSortie() != "0" ? $ms->getQteSortie() : 0);
    //     },0);
    //     return $qte + $qteEntree - $qteSortie;
    // }

    public function getQte(): ?int
    {
        return $this->qte;
    }

    public function setQte(int $qte): self
    {
        $this->qte = $qte;

        return $this;
    }

    public function getDateApprovisionnement(): ?\DateTimeInterface
    {
        return $this->dateApprovisionnement;
    }

    public function setDateApprovisionnement(\DateTimeInterface $dateApprovisionnement): self
    {
        $this->dateApprovisionnement = $dateApprovisionnement;

        return $this;
    }

    public function getDesignation(): ?string
    {
        return $this->designation;
    }

    public function setDesignation(string $designation): self
    {
        $this->designation = $designation;

        return $this;
    }

    public function getPrixUnitaire(): ?float
    {
        return $this->prixUnitaire;
    }

    public function setPrixUnitaire(float $prixUnitaire): self
    {
        $this->prixUnitaire = $prixUnitaire;

        return $this;
    }

    /**
     * @return Collection|MaterielEntree[]
     */
    public function getMaterielEntrees(): Collection
    {
        return $this->materielEntrees;
    }

    public function addMaterielEntree(MaterielEntree $materielEntree): self
    {
        if (!$this->materielEntrees->contains($materielEntree)) {
            $this->materielEntrees[] = $materielEntree;
            $materielEntree->setMateriel($this);
        }

        return $this;
    }

    public function removeMaterielEntree(MaterielEntree $materielEntree): self
    {
        if ($this->materielEntrees->removeElement($materielEntree)) {
            // set the owning side to null (unless already changed)
            if ($materielEntree->getMateriel() === $this) {
                $materielEntree->setMateriel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setMateriel($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getMateriel() === $this) {
                $client->setMateriel(null);
            }
        }

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): self
    {
        $this->departement = $departement;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }
}
