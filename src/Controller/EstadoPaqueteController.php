<?php

namespace App\Controller;

use App\Entity\EstadoPaquete;
use App\Form\EstadoPaqueteType;
use App\Repository\EstadoPaqueteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/estado/paquete")
 */
class EstadoPaqueteController extends AbstractController
{
    /**
     * @Route("/", name="estado_paquete_index", methods={"GET"})
     */
    public function index(Request $request, EstadoPaqueteRepository $estadoPaqueteRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        return $this->render('estado_paquete/index.html.twig', [
            'estado_paquetes' => $estadoPaqueteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="estado_paquete_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $estadoPaquete = new EstadoPaquete();
        $form = $this->createForm(EstadoPaqueteType::class, $estadoPaquete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($estadoPaquete);
            $entityManager->flush();

            return $this->redirectToRoute('estado_paquete_index');
        }

        return $this->render('estado_paquete/new.html.twig', [
            'estado_paquete' => $estadoPaquete,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estado_paquete_show", methods={"GET"})
     */
    public function show(Request $request, EstadoPaquete $estadoPaquete): Response
    {
        $session = $request->getSession();
        $session->set('delete_estado_paquete', "show");

        return $this->render('estado_paquete/show.html.twig', [
            'estado_paquete' => $estadoPaquete,
            'error' => ''
        ]);
    }

    /**
     * @Route("/{id}/edit", name="estado_paquete_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EstadoPaquete $estadoPaquete): Response
    {
        $session = $request->getSession();
        $session->set('delete_estado_paquete', "edit");
        $msg = $session->get('delete_estado_paquete_msg');
        $session->set('delete_estado_paquete_msg', "");
        
        $form = $this->createForm(EstadoPaqueteType::class, $estadoPaquete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estado_paquete_index', [
                'id' => $estadoPaquete->getId(),
            ]);
        }

        return $this->render('estado_paquete/edit.html.twig', [
            'estado_paquete' => $estadoPaquete,
            'form' => $form->createView(),
            'error' => $msg
        ]);
    }

    /**
     * @Route("/{id}", name="estado_paquete_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EstadoPaquete $estadoPaquete): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estadoPaquete->getId(), $request->request->get('_token'))) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($estadoPaquete);
                $entityManager->flush();
            }
            catch(\Exception $e){
                $msg = "No se puede eliminar este estado de paquete";
                $pos = strpos($e->getMessage(), "Integrity constraint violation");
                if($pos !== false)
                    $msg .= " por estar relacionado con otros registros";

                $session = $request->getSession();
                $mostrar = $session->get('delete_estado_paquete');
                $session->set('delete_estado_paquete', "");
                if($mostrar == "show"){
                    return $this->render('estado_paquete/show.html.twig', [
                        'estado_paquete' => $estadoPaquete,
                        'error' => $msg
                    ]);
                }
                else if($mostrar == "edit"){
                    $session->set('delete_estado_paquete_msg', $msg);
                    return $this->redirectToRoute('estado_paquete_edit', [
                        'id' => $estadoPaquete->getId(),
                    ]);
                }
                else
                    return $this->redirectToRoute('estado_paquete_index');
            }
        }

        return $this->redirectToRoute('estado_paquete_index');
    }
}
