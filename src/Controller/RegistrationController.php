<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $msg = "";
            $roles = $user->getRoles();
            foreach($roles as $rol){
                if(($rol == "ROLE_USER" || $rol == "ROLE_SUCURSAL") && !$user->getSucursal())
                    $msg = "Para los roles Comercial y Jefe de Sucursal debe seleccionar una sucursal";
            }
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    'Explorer*2020'
                )
            );
            if($user->getActivo() == "si")
                $user->setActivo(true);
            else
                $user->setActivo(false);
            if($msg == "") {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                // do anything else you need here, like send an email
                return $this->redirectToRoute('user_index');
            }
//            return $guardHandler->authenticateUserAndHandleSuccess(
//                $user,
//                $request,
//                $authenticator,
//                'main' // firewall name in security.yaml
//            );
            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
                'error' => $msg
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'error' => ''
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $session = $request->getSession();
        $session->set('delete_user', "edit");
        $msg = $session->get('delete_user_msg');
        $session->set('delete_user_msg', "");

        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $roles = $user->getRoles();
            foreach($roles as $rol){
                if(($rol == "ROLE_USER" || $rol == "ROLE_SUCURSAL") && !$user->getSucursal())
                    $msg = "Para los roles Comercial y Jefe de Sucursal debe seleccionar una sucursal";
            }

            if($user->getActivo() == "si")
                $user->setActivo(true);
            else
                $user->setActivo(false);
            if($msg == "") {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                // do anything else you need here, like send an email
                return $this->redirectToRoute('user_index');
            }
            return $this->render(
                'registration/register.html.twig', [
                'registrationForm' => $form->createView(),
                'error' => $msg
            ]);
        }

        return $this->render(
            'registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'error' => ''
        ]);
    }



}
