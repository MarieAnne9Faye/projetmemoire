<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DepartementRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
#[ApiResource(
    formats: ['json'],
    normalizationContext: ['groups' => ['departement:read']],
    denormalizationContext: ['groups' => ['departement:write']],
    attributes: ["pagination_enabled" => false],
    collectionOperations:[
        'get',
        'post'
    ],
    itemOperations:[
        'get',
        'put',
        'delete'
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['region' => 'exact'])]
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(["departement:read", "cabinet:write", "cabinet:read", "region:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["departement:read", "departement:write", "cabinet:read", "region:read"])]
    private ?string $libelle = null;

    #[ORM\ManyToOne(inversedBy: 'departements')]
    #[Groups(["departement:read", "departement:write"])]
    private ?Region $region = null;

    #[ORM\OneToMany(mappedBy: 'departement', targetEntity: CabinetMedical::class)]
    private Collection $cabinetMedicals;

    public function __construct()
    {
        $this->cabinetMedicals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): self
    {
        $this->region = $region;

        return $this;
    }

    /**
     * @return Collection<int, CabinetMedical>
     */
    public function getCabinetMedicals(): Collection
    {
        return $this->cabinetMedicals;
    }

    public function addCabinetMedical(CabinetMedical $cabinetMedical): self
    {
        if (!$this->cabinetMedicals->contains($cabinetMedical)) {
            $this->cabinetMedicals[] = $cabinetMedical;
            $cabinetMedical->setDepartement($this);
        }

        return $this;
    }

    public function removeCabinetMedical(CabinetMedical $cabinetMedical): self
    {
        if ($this->cabinetMedicals->removeElement($cabinetMedical)) {
            // set the owning side to null (unless already changed)
            if ($cabinetMedical->getDepartement() === $this) {
                $cabinetMedical->setDepartement(null);
            }
        }

        return $this;
    }
}
