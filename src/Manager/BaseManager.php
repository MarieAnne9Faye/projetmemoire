<?php

namespace App\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseManager
{
    const CODE_KEY = "code";
    const STATUS_KEY = "status";
    const MESSAGE_KEY = "message";
    const DATA_KEY = "data";
    public $em;
    public function  __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function sendResponse($suc, $cod,$msg, $data,$total=null) {
        $retour=array(self::STATUS_KEY=>$suc, self::CODE_KEY=>$cod, self::MESSAGE_KEY=>$msg, self::DATA_KEY=>$data);
        $retour['total'] = $total ?? '';
        return new JsonResponse($retour);
    }

    public function sendResponsePagination($data,$total){
        return count($data)>0?
            $this->sendResponse(true, 200, $data,$total):
            $this->sendResponse(true, 200, $data)
            ;

    }
}