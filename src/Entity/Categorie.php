<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=CategorieRepository::class)
 * @ApiResource(
 *  formats={"json"},
 *  normalizationContext = {"groups"={"categorie_read"}},
 *  collectionOperations={"GET"},
 *  itemOperations={"GET"}
 * )
 * @UniqueEntity(fields={"reference"},message="Ce matériel existe déjà")
 */
class Categorie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"categorie_read","materiel_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Groups({"categorie_read","materiel_read"})
     * @Assert\NotBlank(message="Le nom du fournisseur est obligatoire")
     */
    private $reference;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"categorie_read","materiel_read"})
     *  @Assert\NotBlank(message="Le nom ou le marque est obligatoire")
     */
    private $descriptionCateg;

    /**
     * @ORM\ManyToOne(targetEntity=SousType::class, inversedBy="categories")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"categorie_read","materiel_read"})
     */
    private $sousType;

    /**
     * @ORM\OneToMany(targetEntity=Materiel::class, mappedBy="categorie")
     * @Groups({"categorie_read"})
     */
    private $materiels;

    public function __construct()
    {
        $this->materiels = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): self
    {
        $this->reference = $reference;

        return $this;
    }

    public function getDescriptionCateg(): ?string
    {
        return $this->descriptionCateg;
    }

    public function setDescriptionCateg(string $descriptionCateg): self
    {
        $this->descriptionCateg = $descriptionCateg;

        return $this;
    }

    public function getSousType(): ?SousType
    {
        return $this->sousType;
    }

    public function setSousType(?SousType $sousType): self
    {
        $this->sousType = $sousType;

        return $this;
    }

    /**
     * @return Collection|Materiel[]
     */
    public function getMateriels(): Collection
    {
        return $this->materiels;
    }

    public function addMateriel(Materiel $materiel): self
    {
        if (!$this->materiels->contains($materiel)) {
            $this->materiels[] = $materiel;
            $materiel->setCategorie($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): self
    {
        if ($this->materiels->removeElement($materiel)) {
            // set the owning side to null (unless already changed)
            if ($materiel->getCategorie() === $this) {
                $materiel->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->descriptionCateg;
    }
    
}
