<?php

namespace App\Manager;


use App\Entity\User;
use App\Entity\Profil;
use App\Entity\Statut;
use App\Entity\RendezVous;
use App\Mapping\UserMapping;
use App\Service\FileUploader;
use App\Entity\CabinetMedical;
use App\Entity\DomaineMedical;
use App\Mapping\ClinikMapping;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class ClinikManager extends BaseManager
{
    protected ClinikMapping $clinikMapping;
    protected $userRepo;
    protected $fileUploade;
    public function __construct(EntityManagerInterface $em, ClinikMapping $clinikMapping, UserRepository $userRepo, FileUploader $fileUploade)
    {
        parent::__construct($em);
        $this->clinikMapping = $clinikMapping;
        $this->userRepo = $userRepo;
        $this->fileUploade = $fileUploade;
    }

    

    public function listClinik($data, $filtre){
        
        $cabinets = $this->em->getRepository(CabinetMedical::class)->findListCabinet($data['page'], $filtre);
        
        for($i = 0; $i<sizeof($cabinets); $i++ ){
            
            $cabinets[$i]['logo'] = $this->fileUploade->getUrl($cabinets[$i]['logo']);
        }
        $total = $this->em->getRepository(CabinetMedical::class)->countListCabinet($data['page'], $filtre);
        $msg = $total > 0 ? "" : "Aucun enregistrement";
        $code = $total == 0 ? 204 : 200;
        return $this->sendResponse(true, $code, $msg, $cabinets, $total);
    }
    
    public function domaineDuClinik($id){
        $domaines = $this->em->getRepository(DomaineMedical::class)->findDomaineOfCabinet($id);

        return $this->sendResponse(true, 200, "", $domaines);
    }

    public function addDomaineOfCabinet($data, $id){
        $cabinet = $this->em->getRepository(CabinetMedical::class)->find((int)$id);
        if($cabinet){
            foreach($data['domaines'] as $idDom){
                $domaine = $this->em->getRepository(DomaineMedical::class)->find((int)$idDom);
                if($domaine){
                    $cabinet->addDomaineMedical($domaine);
                }
            }
            $this->em->flush();
        }
        
        return $this->sendResponse(true, 201, "Domaines ajoutés avec succès!", "");
    }
    
    public function addRv($data)
    {
        if(!isset($data['date']) || $data['date'] == ""){
            return $this->sendResponse(false, 500, "Veuillez choisir une date", "");
        }

        if(!isset($data['horaire']) || $data['horaire'] == ""){
            return $this->sendResponse(false, 500, "Veuillez choisir un horaire", "");
        }

        if(!isset($data['domaine']) || $data['domaine'] == ""){
            return $this->sendResponse(false, 500, "Veuillez choisir une domaine pour votre rendez-vous", "");
        }

        $data['rv'] =  new RendezVous();
        $data['domaine'] = $this->em->getRepository(DomaineMedical::class)->find((int)$data['domaine']);
        $data['cabinet'] = $this->em->getRepository(CabinetMedical::class)->find((int)$data['cabinet']);
        $data['statut'] = $this->em->getRepository(Statut::class)->findOneBy(['libelle' => 'EN ATTENTE']);

        $newRv = $this->clinikMapping->rvMapping($data);
        if($newRv instanceof RendezVous){
            $this->em->persist($newRv);
            $this->em->flush();
            $data = $this->clinikMapping->hydrateRv($newRv);
            $msg = "Deamnde envoyé avec succès!";
            return $this->sendResponse(true, 201, $msg, $data);
        }
        return $this->sendResponse(false, 500, "Une erreur s'est produite!", "");
    }

    public function mesRv($user, $page)
    {
        $data['rv'] = $this->em->getRepository(RendezVous::class)->mesRv($user, $page);
        $total = $this->em->getRepository(RendezVous::class)->countmesRv($user);

        $data['encours'] = (string)$this->em->getRepository(RendezVous::class)->countmesRvStatut($user, "EN COURS");
        $data['attente']= (string)$this->em->getRepository(RendezVous::class)->countmesRvStatut($user, "EN ATTENTE");
        $data['annuler'] = (string)$this->em->getRepository(RendezVous::class)->countmesRvStatut($user, "ANNULE");
        $data['termine'] = (string)$this->em->getRepository(RendezVous::class)->countmesRvStatut($user, "TERMINE");

        $msg = $total == 0 ? "Vous n'avez aucun rendez-vous!" : "";
        $code = $total == 0 ? 204 : 200;
        return $this->sendResponse(false, $code, $msg, $data, $total);
    }
}
