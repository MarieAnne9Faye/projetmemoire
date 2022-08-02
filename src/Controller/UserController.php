<?php

namespace App\Controller;

use Datetime;
use App\Entity\User;
use App\Service\FileUploader;
use App\Repository\ProfilRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class UserController extends AbstractController
{

    public function __invoke(Request $req, UserPasswordHasherInterface $hash, ProfilRepository $profilRepo, FileUploader $uploader): User | Response
    {
        $uploadedFile = $req->files->get('photo');
        $data = $req->request->all();
        //dd($data);
        $user = new User();
        $profil = $profilRepo->find((int)$data['profil']);
        $user->setPrenom($data['prenom'])
            ->setNom($data['nom'])
            ->setEmail($data['email'])
            ->setTelephone($data['telephone'])
            ->setAdresse($data['adresse'])
            ->setDateNaissance(new Datetime($data['dateNaissance']))
            ->setPassword($hash->hashPassword($user, $data['password']))
            ->setProfil($profil)
            ->setRoles(['ROLE_'.strtoupper($profil->getLibelle())]);
        
        if($uploadedFile){
            $user->setPhoto($uploader->upload($uploadedFile));
        }
    
        return $user;

    }
}
