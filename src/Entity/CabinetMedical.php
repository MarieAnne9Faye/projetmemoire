<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Controller\CabinetController;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CabinetMedicalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;

#[ORM\Entity(repositoryClass: CabinetMedicalRepository::class)]
#[ApiResource(
    formats: ['json'],
    normalizationContext: ['groups' => ['cabinet:read']],
    denormalizationContext: ['groups' => ['cabinet:write']],
    attributes: ["pagination_enabled" => false],
    collectionOperations:[
        'get',
        'post'=> [
            'controller' => CabinetController::class,
            "deserialize" => false,
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'nom' => [
                                        'type' => 'string'
                                    ],
                                    'logo' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                    'adresse' => [
                                        'type' => 'string'
                                    ],
                                    'departement' => [
                                        'type' => 'integer'
                                    ],
                                    'telephone' => [
                                        'type' => 'string'
                                    ],
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ],
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['departement' => 'exact'])]
class CabinetMedical
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(["cabinet:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(["cabinet:read"])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["cabinet:read", "cabinet:write"])]
    private ?string $logo = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["cabinet:read", "cabinet:write"])]
    private ?string $adresse = null;


    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["cabinet:read", "cabinet:write"])]
    private ?string $telephone = null;

    #[ORM\Column]
    #[Groups(["cabinet:read"])]
    private ?bool $isActived = null;

    #[ORM\OneToMany(mappedBy: 'cabinetMedical', targetEntity: User::class)]
    private Collection $personnel;

    #[ORM\ManyToOne(inversedBy: 'cabinetMedicals')]
    private ?User $adminCabinet = null;

    #[ORM\ManyToMany(targetEntity: DomaineMedical::class, inversedBy: 'cabinetMedicals')]
    private Collection $domaineMedical;

    #[ORM\ManyToOne(inversedBy: 'cabinetMedicals')]
    #[Groups(["cabinet:read", "cabinet:write"])]
    private ?Departement $departement = null;

    #[ORM\OneToMany(mappedBy: 'cabinetMedical', targetEntity: RendezVous::class)]
    private Collection $rendezVouses;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[Groups(["cabinet:read"])]
    private ?User $medecin = null;

    public function __construct()
    {
        $this->personnel = new ArrayCollection();
        $this->domaineMedical = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }


    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function isIsActived(): ?bool
    {
        return $this->isActived;
    }

    public function setIsActived(bool $isActived): self
    {
        $this->isActived = $isActived;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPersonnel(): Collection
    {
        return $this->personnel;
    }

    public function addPersonnel(User $personnel): self
    {
        if (!$this->personnel->contains($personnel)) {
            $this->personnel[] = $personnel;
            $personnel->setCabinetMedical($this);
        }

        return $this;
    }

    public function removePersonnel(User $personnel): self
    {
        if ($this->personnel->removeElement($personnel)) {
            // set the owning side to null (unless already changed)
            if ($personnel->getCabinetMedical() === $this) {
                $personnel->setCabinetMedical(null);
            }
        }

        return $this;
    }

    public function getAdminCabinet(): ?User
    {
        return $this->adminCabinet;
    }

    public function setAdminCabinet(?User $adminCabinet): self
    {
        $this->adminCabinet = $adminCabinet;

        return $this;
    }

    /**
     * @return Collection<int, DomaineMedical>
     */
    public function getDomaineMedical(): Collection
    {
        return $this->domaineMedical;
    }

    public function addDomaineMedical(DomaineMedical $domaineMedical): self
    {
        if (!$this->domaineMedical->contains($domaineMedical)) {
            $this->domaineMedical[] = $domaineMedical;
        }

        return $this;
    }

    public function removeDomaineMedical(DomaineMedical $domaineMedical): self
    {
        $this->domaineMedical->removeElement($domaineMedical);

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

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVouses(): Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): self
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses[] = $rendezVouse;
            $rendezVouse->setCabinetMedical($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): self
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getCabinetMedical() === $this) {
                $rendezVouse->setCabinetMedical(null);
            }
        }

        return $this;
    }

    public function getMedecin(): ?User
    {
        return $this->medecin;
    }

    public function setMedecin(?User $medecin): self
    {
        $this->medecin = $medecin;

        return $this;
    }
}
