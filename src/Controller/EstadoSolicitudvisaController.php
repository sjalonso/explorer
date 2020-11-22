<?php

namespace App\Controller;

use App\Entity\EstadoSolicitudvisa;
use App\Form\EstadoSolicitudvisaType;
use App\Repository\EstadoSolicitudvisaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/estado/solicitudvisa")
 */
class EstadoSolicitudvisaController extends AbstractController
{
    /**
     * @Route("/", name="estado_solicitudvisa_index", methods={"GET"})
     */
    public function index(EstadoSolicitudvisaRepository $estadoSolicitudvisaRepository): Response
    {
        return $this->render('estado_solicitudvisa/index.html.twig', [
            'estado_solicitudvisas' => $estadoSolicitudvisaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="estado_solicitudvisa_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $estadoSolicitudvisa = new EstadoSolicitudvisa();
        $form = $this->createForm(EstadoSolicitudvisaType::class, $estadoSolicitudvisa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($estadoSolicitudvisa);
            $entityManager->flush();

            return $this->redirectToRoute('estado_solicitudvisa_index');
        }

        return $this->render('estado_solicitudvisa/new.html.twig', [
            'estado_solicitudvisa' => $estadoSolicitudvisa,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estado_solicitudvisa_show", methods={"GET"})
     */
    public function show(EstadoSolicitudvisa $estadoSolicitudvisa): Response
    {
        return $this->render('estado_solicitudvisa/show.html.twig', [
            'estado_solicitudvisa' => $estadoSolicitudvisa,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="estado_solicitudvisa_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EstadoSolicitudvisa $estadoSolicitudvisa): Response
    {
        $form = $this->createForm(EstadoSolicitudvisaType::class, $estadoSolicitudvisa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estado_solicitudvisa_index', [
                'id' => $estadoSolicitudvisa->getId(),
            ]);
        }

        return $this->render('estado_solicitudvisa/edit.html.twig', [
            'estado_solicitudvisa' => $estadoSolicitudvisa,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estado_solicitudvisa_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EstadoSolicitudvisa $estadoSolicitudvisa): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estadoSolicitudvisa->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($estadoSolicitudvisa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('estado_solicitudvisa_index');
    }
}
