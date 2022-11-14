<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\UserControllers;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\InfoUserController;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ApiResource(
    formats: ['json'],
    normalizationContext: ['groups' => ['user:read']],
    denormalizationContext: ['groups' => ['user:write']],
    collectionOperations:[
        'get',
        'post' => [
            'controller' => UserControllers::class,
            "deserialize" => false,
            'openapi_context' => [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'prenom' => [
                                        'type' => 'string'
                                    ],
                                    'nom' => [
                                        'type' => 'string'
                                    ],
                                    'email' => [
                                        'type' => 'string'
                                    ],
                                    'telephone' => [
                                        'type' => 'string'
                                    ],
                                    'password' => [
                                        'type' => 'string'
                                    ],
                                    'dateNaissance' => [
                                        'type' => 'date'
                                    ],
                                    'adresse' => [
                                        'type' => 'string'
                                    ],
                                    'password' => [
                                        'type' => 'string'
                                    ],
                                    'photo' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                    'profil' => [
                                        'type' => 'string'
                                    ],
                                    'genre' => [
                                        'type' => 'string'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],

        ]
    ],
    itemOperations:[
        'get',
        'put',
        'delete'
    ]
)]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(
                fields: ['email'],
                errorPath: 'email',
                message: 'Cet email est déjà utilisé.',
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    #[Groups(["user:read"])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(["user:read"])]
    #[Assert\Email()]
    private ?string $email = null;

    #[ORM\Column]
    #[Groups(["user:read"])]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(["user:write"])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(["user:read", "user:write"])]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Groups(["user:read", "user:write"])]
    private ?string $nom = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?string $telephone = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?\DateTimeInterface $dateNaissance = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?string $adresse = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[Groups(["user:read", "user:write"])]
    private ?Profil $profil = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(["user:read", "user:write"])]
    private ?string $photo = null;

    #[ORM\ManyToOne(inversedBy: 'personnel')]
    private ?CabinetMedical $cabinetMedical = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: RendezVous::class)]
    private Collection $rendezVouses;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $genre = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Consultation::class)]
    private Collection $consultations;


    public function __construct()
    {
        //$this->roles = ['ROLE_'.strtoupper($this->profil->getLibelle())];
        $this->cabinetMedicals = new ArrayCollection();
        $this->rendezVouses = new ArrayCollection();
        $this->consultations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(?\DateTimeInterface $dateNaissance): self
    {
        $this->dateNaissance = $dateNaissance;

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

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getCabinetMedical(): ?CabinetMedical
    {
        return $this->cabinetMedical;
    }

    public function setCabinetMedical(?CabinetMedical $cabinetMedical): self
    {
        $this->cabinetMedical = $cabinetMedical;

        return $this;
    }


    public function addCabinetMedical(CabinetMedical $cabinetMedical): self
    {
        if (!$this->cabinetMedicals->contains($cabinetMedical)) {
            $this->cabinetMedicals[] = $cabinetMedical;
            $cabinetMedical->setAdminCabinet($this);
        }

        return $this;
    }

    public function removeCabinetMedical(CabinetMedical $cabinetMedical): self
    {
        if ($this->cabinetMedicals->removeElement($cabinetMedical)) {
            // set the owning side to null (unless already changed)
            if ($cabinetMedical->getAdminCabinet() === $this) {
                $cabinetMedical->setAdminCabinet(null);
            }
        }

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
            $rendezVouse->setPatient($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): self
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getPatient() === $this) {
                $rendezVouse->setPatient(null);
            }
        }

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

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
            $consultation->setPatient($this);
        }

        return $this;
    }

    public function removeConsultation(Consultation $consultation): self
    {
        if ($this->consultations->removeElement($consultation)) {
            // set the owning side to null (unless already changed)
            if ($consultation->getPatient() === $this) {
                $consultation->setPatient(null);
            }
        }

        return $this;
    }
}
