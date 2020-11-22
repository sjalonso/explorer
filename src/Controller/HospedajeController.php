<?php

namespace App\Controller;

use App\Entity\Hospedaje;
use App\Form\HospedajeType;
use App\Repository\HospedajeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hospedaje")
 */
class HospedajeController extends AbstractController
{
    /**
     * @Route("/", name="hospedaje_index", methods={"GET"})
     */
    public function index(HospedajeRepository $hospedajeRepository): Response
    {
        return $this->render('hospedaje/index.html.twig', [
            'hospedajes' => $hospedajeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="hospedaje_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $hospedaje = new Hospedaje();
        $form = $this->createForm(HospedajeType::class, $hospedaje);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hospedaje);
            $entityManager->flush();

            return $this->redirectToRoute('hospedaje_index');
        }

        return $this->render('hospedaje/new.html.twig', [
            'hospedaje' => $hospedaje,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hospedaje_show", methods={"GET"})
     */
    public function show(Hospedaje $hospedaje): Response
    {
        return $this->render('hospedaje/show.html.twig', [
            'hospedaje' => $hospedaje,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="hospedaje_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Hospedaje $hospedaje): Response
    {
        $form = $this->createForm(HospedajeType::class, $hospedaje);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hospedaje_index', [
                'id' => $hospedaje->getId(),
            ]);
        }

        return $this->render('hospedaje/edit.html.twig', [
            'hospedaje' => $hospedaje,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hospedaje_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Hospedaje $hospedaje): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hospedaje->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hospedaje);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hospedaje_index');
    }
}
