<?php

namespace App\Controller;

use App\Entity\Areopuerto;
use App\Form\AreopuertoType;
use App\Repository\AreopuertoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/areopuerto")
 */
class AreopuertoController extends AbstractController
{
    /**
     * @Route("/", name="areopuerto_index", methods={"GET"})
     */
    public function index(Request $request, AreopuertoRepository $areopuertoRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        return $this->render('areopuerto/index.html.twig', [
            'areopuertos' => $areopuertoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="areopuerto_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $areopuerto = new Areopuerto();
        $form = $this->createForm(AreopuertoType::class, $areopuerto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($areopuerto);
            $entityManager->flush();

            return $this->redirectToRoute('areopuerto_index');
        }

        return $this->render('areopuerto/new.html.twig', [
            'areopuerto' => $areopuerto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="areopuerto_show", methods={"GET"})
     */
    public function show(Areopuerto $areopuerto): Response
    {
        return $this->render('areopuerto/show.html.twig', [
            'areopuerto' => $areopuerto,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="areopuerto_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Areopuerto $areopuerto): Response
    {
        $form = $this->createForm(AreopuertoType::class, $areopuerto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('areopuerto_index', [
                'id' => $areopuerto->getId(),
            ]);
        }

        return $this->render('areopuerto/edit.html.twig', [
            'areopuerto' => $areopuerto,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="areopuerto_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Areopuerto $areopuerto): Response
    {
        if ($this->isCsrfTokenValid('delete'.$areopuerto->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($areopuerto);
            $entityManager->flush();
        }

        return $this->redirectToRoute('areopuerto_index');
    }
}
