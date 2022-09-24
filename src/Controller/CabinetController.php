<?php

namespace App\Controller;

use Datetime;
use App\Service\FileUploader;
use App\Entity\CabinetMedical;
use App\Repository\ProfilRepository;
use App\Repository\DepartementRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[AsController]
class CabinetController extends AbstractController
{

    public function __invoke(Request $req, FileUploader $uploader, DepartementRepository $depRepo): CabinetMedical
    {
        $uploadedFile = $req->files->get('logo');
        $data = $req->request->all();
        //dd($data);
        $cabinet = new CabinetMedical();
        $cabinet->setNom($data['nom'])
                ->setAdresse($data['adresse'])
                ->setTelephone($data['telephone'])
                ->setDepartement($depRepo->find((int)$data['departement']) ?? null)
                ->setIsActived(false);
        
        if($uploadedFile){
            $cabinet->setLogo($uploader->upload($uploadedFile));
        }
    
        return $cabinet;

    }
}
