<?php
namespace App\Controller;



use App\Entity\Histo;
use App\Entity\Roles;
use App\Entity\User;
use App\Entity\UsersRoles;
use App\Form\RoleType;
use App\Form\RoleUpdateType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use App\Manager\HistoryManager;



class RoleController extends FOSRestController
{

    /**
     * @Rest\Get("/roles")
     */
    public function getRolesAction(Request $request): View
    {
        $roles = $this->get('doctrine.orm.entity_manager')
            ->getRepository(Roles::class)
            ->findAll();

        return View::create($roles, Response::HTTP_OK);
    }


    /**
     * @Rest\Get("/roles/modif")
     */
    public function getRolesModifiedAction(Request $request): View
    {
        $roles = $this->get('doctrine.orm.entity_manager')
            ->getRepository(Roles::class)
            ->findModified();

        return View::create($roles, Response::HTTP_OK);
    }



    /**
     * @Rest\Get("/roles/modif1")
     * @QueryParam(name="id", requirements="\d+", default="")
     */
    public function getRolesModified1Action(Request $request, ParamFetcher $paramFetcher)
    {

        $array=[];

        $em = $this->get('doctrine.orm.entity_manager');

        $id_user = $paramFetcher->get('id');


        if ($id_user==""){
            $role = $em
                ->getRepository(Roles::class)
                ->findModified();
            return View::create($role, Response::HTTP_OK);
        }

        $role_id = $em
            ->getRepository(UsersRoles::class)
            ->findRoleId($id_user);

        if(count($role_id)!=0){

            foreach ($role_id as $key => $value) {
                array_push($array,$value['id_role']);
            }

            $role = $em
                ->getRepository(Roles::class)
                ->findRole($array);

            return View::create($role, Response::HTTP_OK);
        }
        else{
            $role = $em
                ->getRepository(Roles::class)
                ->findModified();

            return View::create($role, Response::HTTP_OK);
        }


    }






    /**
     * @Rest\Get("/roles/modif2")
     * @QueryParam(name="id", requirements="\d+", default="")
     */
    public function getRolesModified2Action(Request $request, ParamFetcher $paramFetcher)
    {

        $em = $this->get('doctrine.orm.entity_manager');

        $id_user = $paramFetcher->get('id');


        if ($id_user==""){
            return View::create([], Response::HTTP_OK);
        }

        $role_id = $em
            ->getRepository(UsersRoles::class)
            ->findRoleId1($id_user);

        if(count($role_id)!=0) {
            return View::create($role_id, Response::HTTP_OK);
        }
        else{
            return View::create([], Response::HTTP_OK);
        }

    }







    /**
     * @Rest\Post("/GridStateRole")
     *
     */

    public function getGridStateRoleAction(Request $request){

        $em = $this->get('doctrine.orm.entity_manager');

        $sort=$request->get('sort');
        $skip=$request->get('skip');
        $take=$request->get('take');
        $filter=$request->get('filter');

        if(count($filter['filters'])==0 && count($sort[0]) == 2){

            $data1 = $em
                ->getRepository(Roles::class)
                ->FindWithoutFilter($skip, $take, $sort[0]['field'], $sort[0]['dir']);

            $total1 = $em
                ->getRepository(Roles::class)
                ->FindCount();

            $response = array("Count"=>$total1, "Role"=>$data1);

        }
        if(count($filter['filters'])==0 && count($sort[0]) == 1){

            $data1 = $em
                ->getRepository(Roles::class)
                ->FindWithoutFilterAndSort($skip, $take);

            $total1 = $em
                ->getRepository(Roles::class)
                ->FindCount();

            $response = array("Count"=>$total1, "Role"=>$data1);

        }

        if(count($filter['filters'])!=0 && count($sort[0]) == 2){

            $data2 = $em
                ->getRepository(Roles::class)
                ->FindWithFilter($skip, $take, $sort[0]['field'], $sort[0]['dir'],$filter['filters']);


            $total = $em
                ->getRepository(Roles::class)
                ->FindCountWithFilter($filter['filters']);

            $response = array("Count"=>$total, "Role"=>$data2);

        }
        if(count($filter['filters'])!=0 && count($sort[0]) == 1){

            $data2 = $em
                ->getRepository(Roles::class)
                ->FindWithFilter1($skip, $take,$filter['filters']);


            $total = $em
                ->getRepository(Roles::class)
                ->FindCountWithFilter($filter['filters']);

            $response = array("Count"=>$total, "Role"=>$data2);

        }

        return View::create($response, Response::HTTP_OK);
    }




    /**
     * @Rest\Post("/roles")
     * @param Request $request
     * @return View
     */
    public function postRoleAction(Request $request, HistoryManager $historyManager)
    {

        $em = $this->get('doctrine.orm.entity_manager');

        $MaxId=$em
            ->getRepository(Roles::class)
            ->findMaxId();

        $Id=intval($MaxId[0][1])+1;

        $date=date("Y-m-d H:i:s");
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $usr->getUsername();





        $result= $historyManager->setHistory('roles',$Id,'Insert',$date,$usr);


        $Role = new Roles();

        $form = $this->createForm(RoleType::class,$Role);
        $form->submit($request->request->all());

        if ($form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($Role);
            $em->flush();

            $total = $em
                ->getRepository(Roles::class)
                ->FindCount();

            $response = array("Count"=>$total, "Role"=>$Role);

            return View::create($response, Response::HTTP_CREATED);
        }
        else {
            return View::create($form, Response::HTTP_CREATED);
        }



    }

    /**
     * @Rest\Delete("/roles")
     * @QueryParam(name="id", requirements="\d+", default="")
     */
    public function deleteRoleAction(Request $request,ParamFetcher $paramFetcher, HistoryManager $historyManager)
    {
        $ID = $paramFetcher->get('id');
        $date=date("Y-m-d H:i:s");
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $usr->getUsername();


        $result= $historyManager->setHistory('roles',$ID,'Delete',$date,$usr);



        $em = $this->get('doctrine.orm.entity_manager');
        $role = $em->getRepository(Roles::class)
            ->find($ID);

        if ($role) {
            $em->remove($role);
            $em->flush();

            $total = $em
                ->getRepository(Roles::class)
                ->FindCount();

            $response = array("Count"=>$total, "Role"=>$role);

            return View::create($response, Response::HTTP_NO_CONTENT);
        }
        else{
            return View::create([], Response::HTTP_NO_CONTENT);
        }

    }


    /**
     * @Rest\Put("/roles")
     * @QueryParam(name="id", requirements="\d+", default="")
     */

    public function putRoleAction(Request $request,ParamFetcher $paramFetcher, HistoryManager $historyManager)
    {
        $ID = $paramFetcher->get('id');
        $date=date("Y-m-d H:i:s");
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $usr->getUsername();


        $result= $historyManager->setHistory('roles',$ID,'Update',$date,$usr);




        $em = $this->get('doctrine.orm.entity_manager');

        $role = $em->getRepository(Roles::class)
            ->find($ID);

        if (empty($role)) {
            return View::create(['message' => 'Role not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(RoleUpdateType::class, $role);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->merge($role);
            $em->flush();

            $total = $em
                ->getRepository(Roles::class)
                ->FindCount();

            $response = array("Count"=>$total, "Role"=>$role);

            return View::create($response, Response::HTTP_CREATED);
        }
        else{
            return View::create($form, Response::HTTP_CREATED);
        }
    }

}