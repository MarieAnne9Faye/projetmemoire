<?php

namespace App\Manager;


use Datetime;
use App\Entity\User;
use App\Entity\Profil;
use App\Mapping\UserMapping;
use App\Service\FileUploader;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraints\Date;

class UserManager extends BaseManager
{
    protected UserMapping $userMapping;
    protected $userRepo;
    protected $fileUploader;
    public function __construct(EntityManagerInterface $em, UserMapping $userMapping, UserRepository $userRepo, FileUploader $fileUploader)
    {
        parent::__construct($em);
        $this->userMapping = $userMapping;
        $this->userRepo = $userRepo;
        $this->fileUploader = $fileUploader;
    }

    

    public function inscription($data){
        
        if(!isset($data['prenom']) || $data['prenom'] == ''){
            return $this->sendResponse(false,500, array("message"=>"Prénom obligatoire"));
        }
        if(!isset($data['nom']) || $data['nom'] == ''){
            return $this->sendResponse(false,500, array("message"=>"Nom obligatoire"));
        }
        if(!isset($data['dateNaissance']) || $data['dateNaissance'] == ''){
            return $this->sendResponse(false,500, array("message"=>"Date de obligatoire"));
        }
        
        if(!isset($data['email']) || $data['email'] == ''){
            return $this->sendResponse(false,500, array("message"=>"Email obligatoire"));
        }
        
        if(!isset($data['password']) || $data['password'] == ''){
            return $this->sendResponse(false,500, array("message"=>"Le code secret est obligatoire"));
        }
        
        if($data['profil']){
            $data['profil'] = $this->em->getRepository(Profil::class)->findOneBy(['libelle' => $data['profil']]);
        }
        
        $user = new User();

        $objet = $this->userMapping->inscriptionMapping($user, $data);

        $this->userRepo->add($objet, true);
        
        $data = $this->userMapping->hydrateInscription($objet);        
        return $this->sendResponse(true, 201, "Inscription fait avec succès", $data);
    }

    public function code_secret($data) {
        $user = $this->userRepo->find($data['id']);
        if(!$user){
            return $this->sendResponse(false,500, array("message"=>"Cet utilisateur n'existe pas"));
        }

        $obj = $this->userMapping->secretCodeMapping($data, $user);
        $this->em->flush();

        return $this->sendResponse(true,201, array("message"=>"Code secret modifié avec succès"));
    }
    
    public function avatar($data) {
        $user = $this->userRepo->find($data['id']);
        if(!$user){
            return $this->sendResponse(true,201, array("message"=>"Cet utilisateur n'existe pas"));
        }
        
        $objet = $this->userMapping->avatarMapping($data, $user);
        $this->em->flush();
        
        $data = $this->userMapping->hydrateUser($objet);        
        return $this->sendResponse(true,201, array("message"=>"Photo de profil ajoutée avec succès", "data"=>$data));
    }

    public function infoUser($user)
    {
        $res = date_diff($user->getDateNaissance(), new Datetime());
        $data = [
            "id" => $user->getId(),
            "prenom" => $user->getPrenom(),
            "nom" => $user->getNom(),
            "photo" => $this->fileUploader->getUrl($user->getPhoto()),
            "email" => $user->getEmail(),
            "genre" => $user->getGenre(),
            "telephone" => $user->getTelephone(),
            "age" => $res->format("%y"),
            "profil" => $user->getProfil()->getLibelle()
        ];

        return $this->sendResponse(true,200,"", $data);
    }
    
}
