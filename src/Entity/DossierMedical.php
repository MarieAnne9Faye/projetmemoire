<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\DossierMedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DossierMedicalRepository::class)]
#[ApiResource]
class DossierMedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $groupeSanguin = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

    #[ORM\OneToMany(mappedBy: 'dossierMedical', targetEntity: Maladie::class)]
    private Collection $maladies;

    #[ORM\OneToMany(mappedBy: 'dossierMedical', targetEntity: Consultation::class)]
    private Collection $consultations;

    public function __construct()
    {
        $this->maladies = new ArrayCollection();
        $this->consultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGroupeSanguin(): ?string
    {
        return $this->groupeSanguin;
    }

    public function setGroupeSanguin(?string $groupeSanguin): self
    {
        $this->groupeSanguin = $groupeSanguin;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    /**
     * @return Collection<int, Maladie>
     */
    public function getMaladies(): Collection
    {
        return $this->maladies;
    }

    public function addMalady(Maladie $malady): self
    {
        if (!$this->maladies->contains($malady)) {
            $this->maladies[] = $malady;
            $malady->setDossierMedical($this);
        }

        return $this;
    }

    public function removeMalady(Maladie $malady): self
    {
        if ($this->maladies->removeElement($malady)) {
            // set the owning side to null (unless already changed)
            if ($malady->getDossierMedical() === $this) {
                $malady->setDossierMedical(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Consultation>
     */
    public function getConsultations(): Collection
    {
        return $this->consultations;
    }

    public function addConsultation(Consultation $consultation): self
    {
        if (!$this->consultations->contains($consultation)) {
            $this->consultations[] = $consultation;
            $consultation->setDossierMedical($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getDossierMedical() === $this) {
                $consultation->setDossierMedical(null);
            }
        }

        return $this;
    }
}
