<?php

namespace App\Controller;

use App\Entity\EstadoReserva;
use App\Form\EstadoReservaType;
use App\Repository\EstadoReservaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/estado/reserva")
 */
class EstadoReservaController extends AbstractController
{
    /**
     * @Route("/", name="estado_reserva_index", methods={"GET"})
     */
    public function index(Request $request, EstadoReservaRepository $estadoReservaRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        return $this->render('estado_reserva/index.html.twig', [
            'estado_reservas' => $estadoReservaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="estado_reserva_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $estadoReserva = new EstadoReserva();
        $form = $this->createForm(EstadoReservaType::class, $estadoReserva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($estadoReserva);
            $entityManager->flush();

            return $this->redirectToRoute('estado_reserva_index');
        }

        return $this->render('estado_reserva/new.html.twig', [
            'estado_reserva' => $estadoReserva,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estado_reserva_show", methods={"GET"})
     */
    public function show(Request $request, EstadoReserva $estadoReserva): Response
    {
        $session = $request->getSession();
        $session->set('delete_estado_reserva', "show");

        return $this->render('estado_reserva/show.html.twig', [
            'estado_reserva' => $estadoReserva,
            'error' => ''
        ]);
    }

    /**
     * @Route("/{id}/edit", name="estado_reserva_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EstadoReserva $estadoReserva): Response
    {
        $session = $request->getSession();
        $session->set('delete_estado_reserva', "edit");
        $msg = $session->get('delete_estado_reserva_msg');
        $session->set('delete_estado_reserva_msg', "");
        
        $form = $this->createForm(EstadoReservaType::class, $estadoReserva);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estado_reserva_index', [
                'id' => $estadoReserva->getId(),
            ]);
        }

        return $this->render('estado_reserva/edit.html.twig', [
            'estado_reserva' => $estadoReserva,
            'form' => $form->createView(),
            'error' => $msg
        ]);
    }

    /**
     * @Route("/{id}", name="estado_reserva_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EstadoReserva $estadoReserva): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estadoReserva->getId(), $request->request->get('_token'))) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($estadoReserva);
                $entityManager->flush();
            }
            catch(\Exception $e){
                $msg = "No se puede eliminar este estado de reserva";
                $pos = strpos($e->getMessage(), "Integrity constraint violation");
                if($pos !== false)
                    $msg .= " por estar relacionado con otros registros";

                $session = $request->getSession();
                $mostrar = $session->get('delete_estado_reserva');
                $session->set('delete_estado_reserva', "");
                if($mostrar == "show"){
                    return $this->render('estado_reserva/show.html.twig', [
                        'estado_reserva' => $estadoReserva,
                        'error' => $msg
                    ]);
                }
                else if($mostrar == "edit"){
                    $session->set('delete_estado_reserva_msg', $msg);
                    return $this->redirectToRoute('estado_reserva_edit', [
                        'id' => $estadoReserva->getId(),
                    ]);
                }
                else
                    return $this->redirectToRoute('estado_reserva_index');
            }
        }

        return $this->redirectToRoute('estado_reserva_index');
    }
}
