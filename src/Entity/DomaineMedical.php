<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DomaineMedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DomaineMedicalRepository::class)]
#[ApiResource]
class DomaineMedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $libelle = null;

    #[ORM\ManyToMany(targetEntity: CabinetMedical::class, mappedBy: 'domaineMedical')]
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
            $cabinetMedical->addDomaineMedical($this);
        }

        return $this;
    }

    public function removeCabinetMedical(CabinetMedical $cabinetMedical): self
    {
        if ($this->cabinetMedicals->removeElement($cabinetMedical)) {
            $cabinetMedical->removeDomaineMedical($this);
        }

        return $this;
    }
}
