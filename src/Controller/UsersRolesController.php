<?php
namespace App\Controller;


use App\Entity\Permissions;
use App\Entity\RolesPermissions;
use App\Entity\User;
use App\Form\UserRoleType;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use App\Entity\UsersRoles;
use FOS\RestBundle\View\View;



class UsersRolesController extends FOSRestController
{

    /**
     * @Rest\Get("/UserRoles")
     */

    public function getUserRoleAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $UserRole = $em
            ->getRepository(UsersRoles::class)
            ->findByField();

        return View::create($UserRole, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/test")
     */

    public function gettestAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $total = $em
            ->getRepository(UsersRoles::class)
            ->FindCountWithoutFilter();

        return View::create($total, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/UserRoles1")
     */

    public function getUserRole1Action(Request $request)
    {
        $users_ids=[];
        $user_roles_ids=[];
        $inter=[];
        $final=[];


        $sort=$request->get('sort');
        $skip=$request->get('skip');
        $take=$request->get('take');
        $filter=$request->get('filter');


        if(count($filter['filters'])==0 && count($sort[0]) == 2){

            $em = $this->get('doctrine.orm.entity_manager');

            $UsersId = $em
                ->getRepository(UsersRoles::class)
                ->findWithSortWithoutFilter($skip, $take, $sort[0]['field'], $sort[0]['dir']);

            $total = $em
                ->getRepository(UsersRoles::class)
                ->FindCountWithoutFilter();
        }

        if(count($filter['filters'])==0 && count($sort[0]) == 1){

            $em = $this->get('doctrine.orm.entity_manager');

            $UsersId = $em
                ->getRepository(UsersRoles::class)
                ->findWithoutSortWithoutFilter($skip, $take);

            $total = $em
                ->getRepository(UsersRoles::class)
                ->FindCountWithoutFilter();
        }

        if(count($filter['filters'])!=0 && count($sort[0]) == 2){

            $em = $this->get('doctrine.orm.entity_manager');

            $UsersId = $em
                ->getRepository(UsersRoles::class)
                ->findWithSortWithFilter($skip, $take, $sort[0]['field'], $sort[0]['dir'],$filter['filters']);

            $total = $em
                ->getRepository(UsersRoles::class)
                ->FindCountWithFilter($filter['filters']);
        }

        if(count($filter['filters'])!=0 && count($sort[0]) == 1){

            $em = $this->get('doctrine.orm.entity_manager');

            $UsersId = $em
                ->getRepository(UsersRoles::class)
                ->findWithoutSortWithFilter($skip, $take,$filter['filters']);

            $total = $em
                ->getRepository(UsersRoles::class)
                ->FindCountWithFilter($filter['filters']);
        }


        foreach ($UsersId as $key => $value) {
            array_push($users_ids,$value['username']);
        }


        foreach ($users_ids as $value){

            $roles = $em
                ->getRepository(UsersRoles::class)
                ->findtest1($value);

            foreach ($roles as $key => $value1) {
                array_push($inter,$value1['role']);
            }

            $user_roles_ids['username']=$value;
            $user_roles_ids['role']=$inter;
            $inter=[];

            array_push($final,$user_roles_ids);

        }

        $response = array("Count"=>$total, "UsersRoles"=>$final);


        return View::create($response, Response::HTTP_OK);
    }

    /**
     * @Rest\Get("/GetUserPermissions")
     * @QueryParam(name="username", default="")
     */

    public function getUserRoletestAction(Request $request, ParamFetcher $paramFetcher)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $username = $paramFetcher->get('username');

        $id_user=$em
            ->getRepository(User::class)
            ->findIdUser($username);

        $Roles = $em
            ->getRepository(UsersRoles::class)
            ->findroles($id_user[0]['id']);

        $arrOfRolesIds = [];

        foreach ($Roles as $key => $value) {
            array_push($arrOfRolesIds,$value['id_role']);
        }

        $permissions = $em
            ->getRepository(RolesPermissions::class)
            ->findpermissions($arrOfRolesIds);

        $arrOfPermissionsIds = [];

        foreach ($permissions as $key => $value) {
            array_push($arrOfPermissionsIds,$value['id_permission']);
        }

        $permissionsNames = $em
            ->getRepository(Permissions::class)
            ->findPermissionsNames(array_unique($arrOfPermissionsIds));




        return View::create($permissionsNames, Response::HTTP_OK);
    }




    /**
     * @Rest\Get("/UserRoles/modif")
     * @QueryParam(name="skip", requirements="\d+", default="0")
     * @QueryParam(name="take", requirements="\d+", default="4")
     */

    public function getUserRoleModifiedAction(Request $request, ParamFetcher $paramFetcher)
    {

        $offset = $paramFetcher->get('skip');
        $limit = $paramFetcher->get('take');

        $em = $this->get('doctrine.orm.entity_manager');

        $UserRole = $em
            ->getRepository(UsersRoles::class)
            ->findByField();

        $data=array_slice($UserRole,$offset,$limit);

        $total = $em
            ->getRepository(UsersRoles::class)
            ->FindCount();

        $response = array("Count"=>$total, "UsersRoles"=>$data);

        return View::create($response, Response::HTTP_OK);

    }


    /**
     * @Rest\Post("/UserRoles")
     */
    public function postUserRoleAction(Request $request)
    {

        $UserRole = new UsersRoles();

        $UserRole->setCreatDate(date(DATE_RSS ));
        $UserRole->setModifDate(date(DATE_RSS ));
        $UserRole->setCreatedAt('Admin');


        $form = $this->createForm(UserRoleType::class,$UserRole);
        $form->submit($request->request->all());

        if ($form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($UserRole);
            $em->flush();
            return View::create($UserRole, Response::HTTP_CREATED);
        }
        else {
            return View::create($form, Response::HTTP_CREATED);
        }

    }


    /**
     * @Rest\Post("/UserRoles2")
     */

    public function postUserRole1Action(Request $request)
    {

        $em = $this->get('doctrine.orm.entity_manager');


        $user_id=$request->get('user');

        $role_id=$request->get('role');




        $UserRole = $em->getRepository(UsersRoles::class)
            ->findwithIds($user_id,$role_id);



        $UserRole1 = $em->getRepository(UsersRoles::class)
            ->find($UserRole[0]["id"]);


        $em->remove($UserRole1);
        $em->flush();
        return View::create([], Response::HTTP_NO_CONTENT);


    }

    /**
     * @Rest\Delete("/UserRoles")
     * @QueryParam(name="id", requirements="\d+", default="")
     */

    public function deleteUserRoleAction(Request $request,ParamFetcher $paramFetcher)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $ID = $paramFetcher->get('id');

        $UserRole = $em->getRepository(UsersRoles::class)
            ->find($ID);



            if ($UserRole) {
                $em->remove($UserRole);
                $em->flush();
                return View::create([], Response::HTTP_NO_CONTENT);
            }
            else{
                return View::create(['message' => 'UserRole not found'], Response::HTTP_NOT_FOUND);
            }
    }








    /**
     * @Rest\Put("/UserRoles/{id}")
     */

    public function putUserRoleAction(Request $request): View
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $UserRole = $em->getRepository(UsersRoles::class)
            ->find($request->get('id'));

        if (empty($UserRole)) {
            return View::create(['message' => 'UserRole not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(UserRoleType::class, $UserRole);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->merge($UserRole);
            $em->flush();
            return View::create($UserRole, Response::HTTP_CREATED);
        }
        else{
            return View::create($form, Response::HTTP_CREATED);
        }
    }


}