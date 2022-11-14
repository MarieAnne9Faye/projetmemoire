<?php

namespace App\Controller;

use Rest\Post;
use App\Manager\ClinikManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ClinikController extends AbstractController
{
    protected $clinikManager;
    protected $secu;
    public function __construct(ClinikManager $clinikManager, Security $secu)
    {
        $this->clinikManager = $clinikManager;
        $this->secu = $secu;
    }
    
    #[Rest\Get('/api/sec/cabinets')]
    public function inscription(Request $request)
    {
        $data = $request->request->all();
        $data['page']=$request->query->get('page',1);
        $filtre['region']=$request->query->get('region',null);
        $filtre['departement']=$request->query->get('departement',null);
        $filtre['domaine']=$request->query->get('domaine',null);
        $filtre['cabinet']=$request->query->get('cabinet',null);
        return $this->clinikManager->listClinik($data, $filtre);
    }

    #[Rest\Get('/api/sec/cabinets/domaines/{id}')]
    public function domainesCabinet($id)
    {
        return $this->clinikManager->domaineDuClinik($id);
    }

    #[Rest\Post('/api/sec/rendezvous/demande')]
    public function addRv(Request $request)
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(),true);
        $data['patient'] = $user;
        return $this->clinikManager->addRv($data);
    }

    #[Rest\Get('/api/sec/rendezvous')]
    public function listRv(Request $request)
    {
        $user = $this->getUser()->getId();
        $page = $request->query->get('page',1);
        return $this->clinikManager->mesRv($user, $page);
    }

    
}