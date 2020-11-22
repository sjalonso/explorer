<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\PerfilClass;
use App\Form\PerfilType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     */
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //Asignar role admin por defecto
            $role = $entityManager->getRepository(Role::class)->findOneByName("ROLE_ADMIN");
            $user->setRole($role);
            // encode the plain password
            $password = '123456';
            $salt = null;
            Utils::buildPassword($password, $salt);
            $user->setPassword($password);
            $user->setSalt($salt);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(Request $request, User $user): Response
    {
        $session = $request->getSession();
        $session->set('delete_user', "show");

        return $this->render('user/show.html.twig', [
            'user' => $user,
            'error' => ''
        ]);
    }

//    /**
//     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, User $user): Response
//    {
//        $form = $this->createForm(UserType::class, $user);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('user_index', [
//                'id' => $user->getId(),
//            ]);
//        }
//
//        return $this->render('user/edit.html.twig', [
//            'user' => $user,
//            'form' => $form->createView(),
//        ]);
//    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userLogged = $this->get('security.token_storage')->getToken()->getUser();
            if($userLogged->getId() != $user->getId()) {
                try {
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->remove($user);
                    $entityManager->flush();
                }
                catch(\Exception $e){
                    $msg = "No se puede eliminar este usuario";
                    $pos = strpos($e->getMessage(), "Integrity constraint violation");
                    if($pos !== false)
                        $msg .= " por estar relacionado con otros registros";

                    $session = $request->getSession();
                    $mostrar = $session->get('delete_user');
                    $session->set('delete_user', "");
                    if($mostrar == "show"){
                        return $this->render('user/show.html.twig', [
                            'user' => $user,
                            'error' => $msg
                        ]);
                    }
                    else if($mostrar == "edit"){
                        $session->set('delete_user_msg', $msg);
                        return $this->redirectToRoute('user_edit', [
                            'id' => $user->getId(),
                        ]);
                    }
                    else
                        return $this->redirectToRoute('user_index');
                }
            }
            else
                return $this->render('user/show.html.twig', [
                    'user' => $user,
                    'error' => "No se puede eliminar el usuario autenticado"
                ]);
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/{id}/deshabilitar", name="user_deshabilitar", methods={"DELETE"})
     */
    public function deshabilitar(Request $request, User $user): Response
    {
        if ($this->isCsrfTokenValid('deshabilitar'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            if($user->getActivo())
                $user->setActivo(false);
            else
                $user->setActivo(true);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }
    

    /**
     * @Route("/{id}/resetear", name="user_resetear", methods={"DELETE"})
     */
    public function resetear(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        if ($this->isCsrfTokenValid('resetear'.$user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    '123456'
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    public function addFlashbag($request, $type, $message, $stack = false)
    {
        $flashback = $request->getSession()->getFlashbag();
        if (!$stack)
            $flashback->clear($type, $message);
        $flashback->add($type, $message);
    }

}