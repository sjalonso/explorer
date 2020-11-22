<?php

namespace App\Controller;

use App\Entity\Estadocivil;
use App\Form\EstadocivilType;
use App\Repository\EstadocivilRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/estadocivil")
 */
class EstadocivilController extends AbstractController
{
    /**
     * @Route("/", name="estadocivil_index", methods={"GET"})
     */
    public function index(Request $request, EstadocivilRepository $estadocivilRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        return $this->render('estadocivil/index.html.twig', [
            'estadocivils' => $estadocivilRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="estadocivil_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $estadocivil = new Estadocivil();
        $form = $this->createForm(EstadocivilType::class, $estadocivil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($estadocivil);
            $entityManager->flush();

            return $this->redirectToRoute('estadocivil_index');
        }

        return $this->render('estadocivil/new.html.twig', [
            'estadocivil' => $estadocivil,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estadocivil_show", methods={"GET"})
     */
    public function show(Request $request, Estadocivil $estadocivil): Response
    {
        $session = $request->getSession();
        $session->set('delete_estadocivil', "show");

        return $this->render('estadocivil/show.html.twig', [
            'estadocivil' => $estadocivil,
            'error' => ''
        ]);
    }

    /**
     * @Route("/{id}/edit", name="estadocivil_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Estadocivil $estadocivil): Response
    {
        $session = $request->getSession();
        $session->set('delete_estadocivil', "edit");
        $msg = $session->get('delete_estadocivil_msg');
        $session->set('delete_estadocivil_msg', "");
        
        $form = $this->createForm(EstadocivilType::class, $estadocivil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estadocivil_index', [
                'id' => $estadocivil->getId(),
            ]);
        }

        return $this->render('estadocivil/edit.html.twig', [
            'estadocivil' => $estadocivil,
            'form' => $form->createView(),
            'error' => $msg
        ]);
    }

    /**
     * @Route("/{id}", name="estadocivil_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Estadocivil $estadocivil): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estadocivil->getId(), $request->request->get('_token'))) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($estadocivil);
                $entityManager->flush();
            }
            catch(\Exception $e){
                $msg = "No se puede eliminar este estado civil";
                $pos = strpos($e->getMessage(), "Integrity constraint violation");
                if($pos !== false)
                    $msg .= " por estar relacionado con otros registros";

                $session = $request->getSession();
                $mostrar = $session->get('delete_estadocivil');
                $session->set('delete_estadocivil', "");
                if($mostrar == "show"){
                    return $this->render('estadocivil/show.html.twig', [
                        'estadocivil' => $estadocivil,
                        'error' => $msg
                    ]);
                }
                else if($mostrar == "edit"){
                    $session->set('delete_estadocivil_msg', $msg);
                    return $this->redirectToRoute('estadocivil_edit', [
                        'id' => $estadocivil->getId(),
                    ]);
                }
                else
                    return $this->redirectToRoute('estadocivil_index');
            }
        }

        return $this->redirectToRoute('estadocivil_index');
    }
}
