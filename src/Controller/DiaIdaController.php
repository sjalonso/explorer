<?php

namespace App\Controller;

use App\Entity\DiaIda;
use App\Form\DiaIdaType;
use App\Repository\DiaIdaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dia/ida")
 */
class DiaIdaController extends AbstractController
{
    /**
     * @Route("/", name="dia_ida_index", methods={"GET"})
     */
    public function index(DiaIdaRepository $diaIdaRepository): Response
    {
        return $this->render('dia_ida/index.html.twig', [
            'dia_idas' => $diaIdaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="dia_ida_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $diaIda = new DiaIda();
        $form = $this->createForm(DiaIdaType::class, $diaIda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($diaIda);
            $entityManager->flush();

            return $this->redirectToRoute('dia_ida_index');
        }

        return $this->render('dia_ida/new.html.twig', [
            'dia_ida' => $diaIda,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dia_ida_show", methods={"GET"})
     */
    public function show(DiaIda $diaIda): Response
    {
        return $this->render('dia_ida/show.html.twig', [
            'dia_ida' => $diaIda,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dia_ida_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DiaIda $diaIda): Response
    {
        $form = $this->createForm(DiaIdaType::class, $diaIda);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dia_ida_index', [
                'id' => $diaIda->getId(),
            ]);
        }

        return $this->render('dia_ida/edit.html.twig', [
            'dia_ida' => $diaIda,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dia_ida_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DiaIda $diaIda): Response
    {
        if ($this->isCsrfTokenValid('delete'.$diaIda->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($diaIda);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dia_ida_index');
    }
}
