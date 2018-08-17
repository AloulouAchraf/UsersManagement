<?php
namespace App\Controller;



use App\Entity\Permissions;
use App\Entity\RolesPermissions;
use App\Form\PermissionType;
use App\Form\PermissionUpdateType;
use App\Manager\HistoryManager;
use App\Repository\RolePermissionsRepository;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;



class PermissionController extends FOSRestController
{
    /**
     * @Rest\Get("/permission")
     */
    public function getPermissionAction(Request $request): View
    {
        $permission = $this->get('doctrine.orm.entity_manager')
            ->getRepository(Permissions::class)
            ->findAll();

        return View::create($permission, Response::HTTP_OK);
    }


    /**
     * @Rest\Get("/permissions/modif")
     * @QueryParam(name="id", requirements="\d+", default="")
     */
    public function getPermissionModified1Action(Request $request, ParamFetcher $paramFetcher)
    {
        $array=[];

        $em = $this->get('doctrine.orm.entity_manager');

        $id_role = $paramFetcher->get('id');

        if ($id_role==""){
            $permission = $em
                ->getRepository(Permissions::class)
                ->findModified();
            return View::create($permission, Response::HTTP_OK);
        }

        $permission_id = $em
            ->getRepository(RolesPermissions::class)
            ->findPermissionId($id_role);


        if(count($permission_id)!=0){

            foreach ($permission_id as $key => $value) {
                array_push($array,$value['id_permission']);
            }

            $permission = $em
                ->getRepository(Permissions::class)
                ->findPermission($array);

            return View::create($permission, Response::HTTP_OK);
        }

        else{
            $permission = $em
                ->getRepository(Permissions::class)
                ->findModified();

            return View::create($permission, Response::HTTP_OK);
        }

    }







    /**
     * @Rest\Get("/permission/modif2")
     * @QueryParam(name="id", requirements="\d+", default="")
     */
    public function getPermissionModified2Action(Request $request, ParamFetcher $paramFetcher)
    {

        $em = $this->get('doctrine.orm.entity_manager');

        $id_role = $paramFetcher->get('id');


        if ($id_role==""){
            return View::create([], Response::HTTP_OK);
        }

        $permission_id = $em
            ->getRepository(RolesPermissions::class)
            ->findPermissionId1($id_role);

        if(count($permission_id)!=0) {
            return View::create($permission_id, Response::HTTP_OK);
        }
        else{
            return View::create([], Response::HTTP_OK);
        }

    }












    /**
     * @Rest\Post("/GridStatePermission")
     *
     */

    public function getGridStatePermissionAction(Request $request){

        $em = $this->get('doctrine.orm.entity_manager');

        $sort=$request->get('sort');
        $skip=$request->get('skip');
        $take=$request->get('take');
        $filter=$request->get('filter');



        if(count($filter['filters'])==0 && count($sort[0]) == 2){

            $data1 = $em
                ->getRepository(Permissions::class)
                ->FindWithoutFilter($skip, $take, $sort[0]['field'], $sort[0]['dir']);

            $total1 = $em
                ->getRepository(Permissions::class)
                ->FindCount();

            $response = array("Count"=>$total1, "Permission"=>$data1);

        }

        if(count($filter['filters'])==0 && count($sort[0]) == 1){

            $data1 = $em
                ->getRepository(Permissions::class)
                ->FindWithoutFilterAndSort($skip, $take);

            $total1 = $em
                ->getRepository(Permissions::class)
                ->FindCount();

            $response = array("Count"=>$total1, "Permission"=>$data1);

        }

        if(count($filter['filters'])!=0 && count($sort[0]) == 2){

            $data2 = $em
                ->getRepository(Permissions::class)
                ->FindWithFilter($skip, $take, $sort[0]['field'], $sort[0]['dir'], $filter['filters']);


            $total = $em
                ->getRepository(Permissions::class)
                ->FindCountWithFilter($filter['filters']);

            $response = array("Count"=>$total, "Permission"=>$data2);

        }

        if(count($filter['filters'])!=0 && count($sort[0]) == 1){

            $data2 = $em
                ->getRepository(Permissions::class)
                ->FindWithFilter1($skip, $take, $filter['filters']);


            $total = $em
                ->getRepository(Permissions::class)
                ->FindCountWithFilter($filter['filters']);

            $response = array("Count"=>$total, "Permission"=>$data2);

        }

        return View::create($response, Response::HTTP_OK);
    }


    /**
     * @Rest\Post("/permission")
     * @param Request $request
     * @return View
     */
    public function postPermissionAction(Request $request, HistoryManager $historyManager)
    {

        $em = $this->get('doctrine.orm.entity_manager');

        $MaxId=$em
            ->getRepository(Permissions::class)
            ->findMaxId();

        $Id=intval($MaxId[0][1])+1;


        $date=date("Y-m-d H:i:s");
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $usr->getUsername();


        $result= $historyManager->setHistory('permission',$Id,'Insert',$date,$usr);







        $permission = new Permissions();

        $form = $this->createForm(PermissionType::class,$permission);

        $form->submit($request->request->all());

        if ($form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($permission);
            $em->flush();

            $total = $em
                ->getRepository(Permissions::class)
                ->FindCount();

            $response = array("Count"=>$total, "Permission"=>$permission);

            return View::create($response, Response::HTTP_CREATED);
        }
        else {
            return View::create($form, Response::HTTP_CREATED);
        }
    }

    /**
     * @Rest\Delete("/permission")
     * @QueryParam(name="id", requirements="\d+", default="")
     */
    public function deletePermissionAction(Request $request,ParamFetcher $paramFetcher, HistoryManager $historyManager)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $ID = $paramFetcher->get('id');


        $date=date("Y-m-d H:i:s");
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $usr->getUsername();


        $result= $historyManager->setHistory('permission',$ID,'Delete',$date,$usr);


        $permission = $em->getRepository(Permissions::class)
            ->find($ID);

        if ($permission) {
            $em->remove($permission);
            $em->flush();

            $total = $em
                ->getRepository(Permissions::class)
                ->FindCount();

            $response = array("Count"=>$total, "Permission"=>$permission);

            return View::create($response, Response::HTTP_NO_CONTENT);
        }
        else{
            return View::create([], Response::HTTP_NO_CONTENT);
        }

    }

    /**
     * @Rest\Put("/permission")
     * @QueryParam(name="id", requirements="\d+", default="")
     */

    public function putPermissionAction(Request $request,ParamFetcher $paramFetcher, HistoryManager $historyManager)
    {
        $ID = $paramFetcher->get('id');


        $date=date("Y-m-d H:i:s");
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $usr->getUsername();


        $result= $historyManager->setHistory('permission',$ID,'Update',$date,$usr);

        $em = $this->get('doctrine.orm.entity_manager');

        $permission = $em->getRepository(Permissions::class)
            ->find($ID);

        if (empty($permission)) {
            return View::create(['message' => 'Permission not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(PermissionUpdateType::class, $permission);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->merge($permission);
            $em->flush();

            $total = $em
                ->getRepository(Permissions::class)
                ->FindCount();

            $response = array("Count"=>$total, "Permission"=>$permission);

            return View::create($response, Response::HTTP_CREATED);
        }
        else{
            return View::create($form, Response::HTTP_CREATED);
        }
    }

}