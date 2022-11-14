<?php

namespace App\Mapping;

use App\Service\FileUploader;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class ClinikMapping
{

    protected $passwordHasher;
    protected $serviceFile;
    public function __construct(UserPasswordHasherInterface $passwordHasher, FileUploader $serviceFile)
    {
        $this->passwordHasher = $passwordHasher;
        $this->serviceFile = $serviceFile;
    }

    public function inscriptionMapping($user, $data)
    {
        if($data['password']){
            $plaintextPassword = $data['password'];
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
            $user->setPassword($hashedPassword);
        }

        $dateNaiss = $data['dateNaissance'] ? new \DateTime($data["dateNaissance"]) : $user->getDateNaissance();
        
        $user->setPrenom($data['prenom'] ?? $user->getPrenom());
        $user->setNom($data['nom'] ?? $user->getNom());
        $user->setTelephone($data['telephone'] ?? $user->getTelephone());
        $user->setAdresse($data['adresse'] ?? $user->getAdresse());
        $user->setDateNaissance($dateNaiss);
        $user->setEmail($data['email'] ?? $user->getEmeil());
        $user->setProfil($data['profil'] ?? $user->getProfil());
        $user->setRoles(['ROLE_'.$data['profil']->getLibelle()]);
        if(isset($data['photo'])){    
            $user->setPhoto($this->serviceFile->upload($data['photo']));
        }
        
        return $user;
    }

    public function secretCodeMapping($data, $user)
    {
        $plaintextPassword = $data['code'];
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plaintextPassword);
        $user->setPassword($hashedPassword);
        return $user;
    }

    public function avatarMapping($data, $user)
    {
        if($data['avatar']){    
            $user->setAvatar($this->serviceFile->upload($data['avatar']));
        }
        return $user;
    }


    public function hydrateInscription($user)
    {
            return [
                "id" => $user->getId(),
                "prenom" => $user->getPrenom(),
                "nom" => $user->getNom(),
                "telephone" => $user->getTelephone(),
                "email" => $user->getEmail(),
                "adresse" => $user->getAdresse(),
                "dateNaissance" => date_format($user->getDateNaissance(), "d-m-Y"),
                "roles" => $user->getRoles(),
                "photo" => $user->getPhoto() != null ? $this->serviceFile->getUrl($user->getPhoto()) : null,
            ];
    }

    public function rvMapping($data)
    {
        $rv = $data['rv'];
        
        $date =  new \DateTime($data["date"]);
        $rv->setDate($date)
            ->setHoraire($data['horaire'])
            ->setCabinetMedical($data['cabinet'])
            ->setDomaineMedical($data['domaine'])
            ->setPatient($data['patient'])
            ->setStatut($data['statut']);
        return $rv;
    }

    public function hydrateRv($rv)
    {
        return [
            "id" => $rv->getId(),
            "domaine" => $rv->getDomaineMedical()->getLibelle(),
            "cabinet" => $rv->getCabinetMedical()->getNom(),
            "date"    => date_format($rv->getDate(), "d-m-Y"),
            "horaire" => $rv->getHoraire(),
            "patient" => $rv->getPatient()->getPrenom()." ".$rv->getPatient()->getNom(),
            "statut" => $rv->getStatut()->getLibelle()

        ];
    }
}