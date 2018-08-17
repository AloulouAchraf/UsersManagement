<?php
namespace App\Controller;




use App\Entity\Histo;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;



class HistoController extends FOSRestController
{

    /**
     * @Rest\Post("/Histo")
     */
    public function getHistoAction(Request $request)
    {

        $sort=$request->get('sort');
        $skip=$request->get('skip');
        $take=$request->get('take');
        $filter=$request->get('filter');

        $em = $this->get('doctrine.orm.entity_manager');


        if(count($filter['filters'])==0 && count($sort[0]) == 2){

            $data1 = $em
                ->getRepository(Histo::class)
                ->FindWithoutFilter($skip, $take, $sort[0]['field'], $sort[0]['dir']);

            $total1 = $em
                ->getRepository(Histo::class)
                ->FindCount();

            $response = array("Count"=>$total1, "Histo"=>$data1);

            return View::create($response, Response::HTTP_OK);

        }



    }

}