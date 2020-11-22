<?php

namespace App\Controller;

use App\Entity\EstadoVisa;
use App\Form\EstadoVisaType;
use App\Repository\EstadoVisaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/estado/visa")
 */
class EstadoVisaController extends AbstractController
{
    /**
     * @Route("/", name="estado_visa_index", methods={"GET"})
     */
    public function index(EstadoVisaRepository $estadoVisaRepository): Response
    {
        return $this->render('estado_visa/index.html.twig', [
            'estado_visas' => $estadoVisaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="estado_visa_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $estadoVisa = new EstadoVisa();
        $form = $this->createForm(EstadoVisaType::class, $estadoVisa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($estadoVisa);
            $entityManager->flush();

            return $this->redirectToRoute('estado_visa_index');
        }

        return $this->render('estado_visa/new.html.twig', [
            'estado_visa' => $estadoVisa,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estado_visa_show", methods={"GET"})
     */
    public function show(EstadoVisa $estadoVisa): Response
    {
        return $this->render('estado_visa/show.html.twig', [
            'estado_visa' => $estadoVisa,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="estado_visa_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EstadoVisa $estadoVisa): Response
    {
        $form = $this->createForm(EstadoVisaType::class, $estadoVisa);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estado_visa_index', [
                'id' => $estadoVisa->getId(),
            ]);
        }

        return $this->render('estado_visa/edit.html.twig', [
            'estado_visa' => $estadoVisa,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estado_visa_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EstadoVisa $estadoVisa): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estadoVisa->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($estadoVisa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('estado_visa_index');
    }
}
