<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DepartementRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=DepartementRepository::class)
 * @UniqueEntity(fields={"nomDepartement"},message="Ce département existe déjà")
 * @ApiResource(
 *  formats={"json"},
 *  normalizationContext = {"groups"={"departement_read"}},
 *  collectionOperations={"GET"},
 *  itemOperations={"GET"}
 * )
 */
class Departement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"departement_read","materiel_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60)
     * @Groups({"departement_read","materiel_read"})
     * @Assert\NotBlank(message="Le nom du département est obligatoire")
     */
    private $nomDepartement;

    /**
     * @ORM\Column(type="string", length=80)
     * @Groups({"departement_read","materiel_read"})
     * @Assert\NotBlank(message="Le lieu est obligatoire")
     */
    private $lieu;

    /**
     * @ORM\Column(type="string", length=14,nullable=true)
     * @Assert\Length(max=13,maxMessage="Le contact est au maximun 10 chiffres")
     * @Groups({"departement_read","materiel_read"})
     * @Assert\NotBlank(message="Le contact est obligatoire")
     */
    private $contactDept;

    /**
     * @ORM\OneToMany(targetEntity=Materiel::class, mappedBy="departement")
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

    public function getNomDepartement(): ?string
    {
        return $this->nomDepartement;
    }

    public function setNomDepartement(string $nomDepartement): self
    {
        $this->nomDepartement = $nomDepartement;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getContactDept(): ?string
    {
        return $this->contactDept;
    }

    public function setContactDept(string $contactDept): self
    {
        $this->contactDept = $contactDept;

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
            $materiel->setDepartement($this);
        }

        return $this;
    }

    public function removeMateriel(Materiel $materiel): self
    {
        if ($this->materiels->removeElement($materiel)) {
            // set the owning side to null (unless already changed)
            if ($materiel->getDepartement() === $this) {
                $materiel->setDepartement(null);
            }
        }

        return $this;
    }
    public function __toString()
    {
        return $this->nomDepartement;
    }
}
