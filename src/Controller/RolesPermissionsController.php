<?php
namespace App\Controller;




use App\Entity\RolesPermissions;
use App\Form\RolePermissionsType;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;




class RolesPermissionsController extends FOSRestController
{
    /**
     * @Rest\Get("/RolePermissions")
     */

    public function getRolePermissionsAction(Request $request)
    {
        $RolePermissions = $this->get('doctrine.orm.entity_manager')
            ->getRepository(RolesPermissions::class)
            ->findModified();

        return View::create($RolePermissions, Response::HTTP_OK);

    }


    /**
     * @Rest\Post("/RolePermissions")
     */

    public function postRolePermissionsAction(Request $request)
    {

        $RolePermissions = new RolesPermissions();

        $form = $this->createForm(RolePermissionsType::class,$RolePermissions);

        $form->submit($request->request->all());

        if ($form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($RolePermissions);
            $em->flush();
            return View::create($RolePermissions, Response::HTTP_CREATED);
        }
        else {
            return View::create($form, Response::HTTP_CREATED);
        }

    }


    /**
     * @Rest\Post("/RolePermissions2")
     */

    public function postPermissionsRole1Action(Request $request)
    {

        $em = $this->get('doctrine.orm.entity_manager');


        $role_id=$request->get('role');

        $permission_id=$request->get('permission');




        $PermissionRole = $em->getRepository(RolesPermissions::class)
            ->findwithIds($role_id,$permission_id);



        $PermissionRole1 = $em->getRepository(RolesPermissions::class)
            ->find($PermissionRole[0]["id"]);


        $em->remove($PermissionRole1);
        $em->flush();
        return View::create([], Response::HTTP_NO_CONTENT);


    }

    /**
     * @Rest\Delete("/RolePermissions")
     * @QueryParam(name="id", requirements="\d+", default="")
     */

    public function deleteRolePermissionsAction(Request $request,ParamFetcher $paramFetcher)
    {
        $ID = $paramFetcher->get('id');

        $em = $this->get('doctrine.orm.entity_manager');

        $RolePermissions = $em->getRepository(RolesPermissions::class)
            ->find($ID);

        if ($RolePermissions) {
            $em->remove($RolePermissions);
            $em->flush();
            return View::create([], Response::HTTP_NO_CONTENT);
        }
        else{
            return View::create(['message' => 'RolePermissions not found'], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * @Rest\Put("/RolePermissions/{id}")
     */

    public function putRolePermissionsAction(Request $request): View
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $RolePermissions = $em->getRepository(RolesPermissions::class)
            ->find($request->get('id'));

        if (empty($RolePermissions)) {
            return View::create(['message' => 'RolePermissions not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(RolePermissionsType::class, $RolePermissions);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->merge($RolePermissions);
            $em->flush();
            return View::create($RolePermissions, Response::HTTP_CREATED);
        }
        else{
            return View::create($form, Response::HTTP_CREATED);
        }
    }



    /**
     * @Rest\Post("/RolesPermissions1")
     */

    public function getRolesPermissions1Action(Request $request)
    {
        $roles_ids=[];
        $roles_permission_ids=[];
        $inter=[];
        $final=[];


        $sort=$request->get('sort');
        $skip=$request->get('skip');
        $take=$request->get('take');
        $filter=$request->get('filter');


        if(count($filter['filters'])==0 && count($sort[0]) == 2){

            $em = $this->get('doctrine.orm.entity_manager');

            $RolesId = $em
                ->getRepository(RolesPermissions::class)
                ->findWithSortWithoutFilter($skip, $take, $sort[0]['field'], $sort[0]['dir']);

            $total = $em
                ->getRepository(RolesPermissions::class)
                ->FindCountWithoutFilter();
        }


        if(count($filter['filters'])==0 && count($sort[0]) == 1){

            $em = $this->get('doctrine.orm.entity_manager');

            $RolesId = $em
                ->getRepository(RolesPermissions::class)
                ->findWithoutSortWithoutFilter($skip, $take);

            $total = $em
                ->getRepository(RolesPermissions::class)
                ->FindCountWithoutFilter();
        }

        if(count($filter['filters'])!=0 && count($sort[0]) == 2){

            $em = $this->get('doctrine.orm.entity_manager');

            $RolesId = $em
                ->getRepository(RolesPermissions::class)
                ->findWithSortWithFilter($skip, $take, $sort[0]['field'], $sort[0]['dir'],$filter['filters']);

            $total = $em
                ->getRepository(RolesPermissions::class)
                ->FindCountWithFilter($filter['filters']);
        }

        if(count($filter['filters'])!=0 && count($sort[0]) == 1){

            $em = $this->get('doctrine.orm.entity_manager');

            $RolesId = $em
                ->getRepository(RolesPermissions::class)
                ->findWithoutSortWithFilter($skip, $take,$filter['filters']);

            $total = $em
                ->getRepository(RolesPermissions::class)
                ->FindCountWithFilter($filter['filters']);
        }


        foreach ($RolesId as $key => $value) {
            array_push($roles_ids,$value['role']);
        }


        foreach ($roles_ids as $value){

            $permission = $em
                ->getRepository(RolesPermissions::class)
                ->findtest1($value);

            foreach ($permission as $key => $value1) {
                array_push($inter,$value1['permission']);
            }

            $roles_permission_ids['name']=$value;
            $roles_permission_ids['permission']=$inter;
            $inter=[];

            array_push($final,$roles_permission_ids);

        }

        $response = array("Count"=>$total, "RolesPermissions"=>$final);


        return View::create($response, Response::HTTP_OK);
    }




}