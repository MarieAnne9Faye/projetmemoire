<?php

namespace App\Controller;

use Rest\Post;
use App\Manager\ClinikManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BackOfficeController extends AbstractController
{
    protected $clinikManager;
    public function __construct(ClinikManager $clinikManager)
    {
        $this->clinikManager = $clinikManager;
    }
    
    #[Rest\Post('/api/bo/cabinets/addDomaines/{id}')]
    public function inscription(Request $request, $id)
    {
        $data = $request->request->all();
        return $this->clinikManager->addDomaineOfCabinet($data, $id);
    }

  

    
}