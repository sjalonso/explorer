<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/changepass", name="changepass",  methods={"GET","POST"})
     */
    public function change(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $editForm = null;

        $editForm = $this->createForm('App\Form\PerfilType', $user);
        $error = false;
        $editForm->handleRequest($request);
        if ($editForm->isSubmitted()) {
            if (!$editForm->isValid()) {
                $this->addFlashbag($request, 'error', 'Formulario inválido');
                $error = true;
            }

            $data = $editForm->getData();

            if(!$error) {
                $em = $this->getDoctrine()->getManager();
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $data->getPlainpassword()
                    )
                );
                $em->persist($user);
                $em->flush($user);
                $this->addFlashbag($request, 'notice', 'Contraseña cambiada con éxito.');
            }

        }

        //$this->addFlash('info', 'no se puede cambiar la password');
        return $this->render('user/perfil.html.twig', array(
            'user' => $user,
            'form' => $editForm->createView(),
        ));

    }

    public function addFlashbag($request, $type, $message, $stack = false)
    {
        $flashback = $request->getSession()->getFlashbag();
        if (!$stack)
            $flashback->clear($type, $message);
        $flashback->add($type, $message);
    }

     /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        
    }

}
