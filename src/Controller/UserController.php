<?php

namespace App\Controller;

use Rest\Post;
use App\Manager\UserManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    protected $userManager;
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
    }
    
    #[Rest\Post('/api/inscription')]
    public function inscription(Request $request)
    {
        $data = $request->request->all();
        $data['photo'] = $request->files->get('photo');
        return $this->userManager->inscription($data);
    }

    #[Rest\Post('/api/changePassword/{id}')]
    public function code_secret(Request $request, $id)
    {
         $data = json_decode($request->getContent(),true);
         $data['id'] = $id;

         return $this->userManager->code_secret($data);
    }

    #[Rest\Post('/api/sec/avatar/{id}')]
    public function avatar(Request $request, $id)
    {
         $data['photo'] = $request->files->get('photo');
         $data['id'] = $id;

         return $this->userManager->avatar($data);
    }

    #[Rest\Get('/api/infoUser')]
    public function infoUser()
    {
         $user = $this->getUser();
         return $this->userManager->infoUser($user);
    }

    
}