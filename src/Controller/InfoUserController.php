<?php

namespace App\Controller;

use Datetime;
use App\Entity\User;
use App\Service\FileUploader;
use App\Repository\ProfilRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsController]
class InfoUserController extends AbstractController
{

    public function __invoke(Request $req,FileUploader $uploader, EntityManagerInterface $em, $email): User | Response
    {
        $user = $em->getRepository(User::class)->findOneBy(["email" => $email]);
        
        return $user;

    }
}
