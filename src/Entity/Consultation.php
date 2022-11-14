<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?DossierMedical $dossierMedical = null;

    #[ORM\ManyToOne(inversedBy: 'consultations')]
    private ?User $patient = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $diagnostic = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $examenDemande = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $temperature = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $tension = null;

    #[ORM\Column(nullable: true)]
    private ?int $poids = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $glycemie = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDossierMedical(): ?DossierMedical
    {
        return $this->dossierMedical;
    }

    public function setDossierMedical(?DossierMedical $dossierMedical): self
    {
        $this->dossierMedical = $dossierMedical;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDiagnostic(): ?string
    {
        return $this->diagnostic;
    }

    public function setDiagnostic(?string $diagnostic): self
    {
        $this->diagnostic = $diagnostic;

        return $this;
    }

    public function getExamenDemande(): ?string
    {
        return $this->examenDemande;
    }

    public function setExamenDemande(?string $examenDemande): self
    {
        $this->examenDemande = $examenDemande;

        return $this;
    }

    public function getTemperature(): ?string
    {
        return $this->temperature;
    }

    public function setTemperature(?string $temperature): self
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getTension(): ?string
    {
        return $this->tension;
    }

    public function setTension(?string $tension): self
    {
        $this->tension = $tension;

        return $this;
    }

    public function getPoids(): ?int
    {
        return $this->poids;
    }

    public function setPoids(?int $poids): self
    {
        $this->poids = $poids;

        return $this;
    }

    public function getGlycemie(): ?string
    {
        return $this->glycemie;
    }

    public function setGlycemie(?string $glycemie): self
    {
        $this->glycemie = $glycemie;

        return $this;
    }
}
