<?php
namespace App\Controller;


use App\Entity\Roles;
use App\Entity\User;
use App\Entity\UsersRoles;
use App\Form\UserType;
use App\Form\UserUpdateType;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Request\ParamFetcher;




class UserController extends FOSRestController
{

    /**
     * @Rest\Get("/users")
     * @QueryParam(name="skip", requirements="\d+", default="0")
     * @QueryParam(name="take", requirements="\d+", default="4")
     * @QueryParam(name="field", requirements="[a-z]+", default="id")
     * @QueryParam(name="dir", requirements="[a-z]+", default="asc")
     */
    public function getUserAction(Request $request,ParamFetcher $paramFetcher)
    {
        $em = $this->get('doctrine.orm.entity_manager');

        $offset = $paramFetcher->get('skip');
        $limit = $paramFetcher->get('take');
        $field = $paramFetcher->get('field');
        $dir = $paramFetcher->get('dir');



        $users = $em
            ->getRepository(User::class)
            ->FindWithSort($field,$dir);



        $data=array_slice($users,$offset,$limit);


        $total = $em
            ->getRepository(User::class)
            ->FindCount();

        $response = array("Count"=>$total, "Users"=>$data);

        return View::create($response, Response::HTTP_OK);
    }


    /**
     * @Rest\Post("/GridState")
     *
     */
    public function getGridStateAction(Request $request)

    {
        $em = $this->get('doctrine.orm.entity_manager');

        $sort=$request->get('sort');
        $skip=$request->get('skip');
        $take=$request->get('take');
        $filter=$request->get('filter');

        if(count($filter['filters'])==0){

            $id = $em
                ->getRepository(User::class)
                ->findMinId();

            $data1 = $em
                ->getRepository(User::class)
                ->FindWithoutFilter($id[0]['id'], $skip, $take, $sort[0]['field'], $sort[0]['dir']);

            $total1 = $em
                ->getRepository(User::class)
                ->FindCount();

            $response = array("Count"=>$total1, "Users"=>$data1);

        }
        if(count($filter['filters'])!=0){

            $data2 = $em
                ->getRepository(User::class)
                ->FindWithFilter($sort[0]['field'], $sort[0]['dir'],$filter['filters']);

            $data=array_slice($data2,$skip,$take);

            $total = count($data2);

            $response = array("Count"=>$total, "Users"=>$data);
        }


        return View::create($response, Response::HTTP_OK);
    }


    /**
     * @Rest\Get("/usersTest")
     */
    public function getUserTestAction(Request $request)
    {
        $em = $this->get('doctrine.orm.entity_manager');


        $users = $em
            ->getRepository(User::class)
            ->findAll();


        return View::create($users, Response::HTTP_OK);
    }


    /**
     * @Rest\Get("/users/modif")
     */

    public function getUserModifiedAction(Request $request){

        $em = $this->get('doctrine.orm.entity_manager');

        $users = $em
            ->getRepository(User::class)
            ->findModified1();


        return View::create($users, Response::HTTP_OK);

    }

    /**
     * @Rest\Get("/users/modif1")
     * @QueryParam(name="id", requirements="\d+", default="")
     */

    public function getUserModified1Action(Request $request, ParamFetcher $paramFetcher){

        $em = $this->get('doctrine.orm.entity_manager');

        $id_user = $paramFetcher->get('id');

        $role_id = $em
            ->getRepository(UsersRoles::class)
            ->findRoleId($id_user);


        $role = $em
            ->getRepository(Roles::class)
            ->findRole();


        return View::create($role_id, Response::HTTP_OK);

    }

    /**
     * @Rest\Post("/users/create")
     */

    public function postUserAction(Request $request): View
    {
        $User = new User();

        $form = $this->createForm(UserType::class,$User);
        $form->submit($request->request->all());


        if ($form->isValid())
        {
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($User);
            $em->flush();

            $total = $em
                ->getRepository(User::class)
                ->FindCount();

            $response = array("Count"=>$total, "Users"=>$User);

            return View::create($response, Response::HTTP_CREATED);
        }
        else {
            return View::create($form, Response::HTTP_CREATED);
        }
    }

    /**
     * @Rest\Delete("/users/destroy")
     * @QueryParam(name="id", requirements="\d+", default="")
     */
    public function deleteUserAction(Request $request,ParamFetcher $paramFetcher): View
    {
        $ID = $paramFetcher->get('id');


        $em = $this->get('doctrine.orm.entity_manager');
        $user = $em->getRepository(User::class)
            ->find($ID);

        if ($user) {
            $em->remove($user);
            $em->flush();

            $total = $em
                ->getRepository(User::class)
                ->FindCount();

            $response = array("Count"=>$total, "Users"=>$user);

            return View::create($response,Response::HTTP_NO_CONTENT);
        }
        else{
            return View::create([], Response::HTTP_NO_CONTENT);
        }

    }

    /**
     * @Rest\Put("/users/update")
     * @QueryParam(name="id", requirements="\d+", default="")
     */

    public function putUserAction(Request $request,ParamFetcher $paramFetcher)
    {
        $ID = $paramFetcher->get('id');

        $em = $this->get('doctrine.orm.entity_manager');

        $user = $em->getRepository(User::class)
            ->find($ID);

        if (empty($user)) {
            return View::create(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }

        $form = $this->createForm(UserUpdateType::class, $user);
        $form->submit($request->request->all());

        if ($form->isValid()) {
            $em->merge($user);
            $em->flush();


            $total = $em
                ->getRepository(User::class)
                ->FindCount();

            $response = array("Count"=>$total, "Users"=>$user);

            return View::create($response, Response::HTTP_CREATED);
        }
        else{
            return View::create($form, Response::HTTP_CREATED);
        }
    }

}