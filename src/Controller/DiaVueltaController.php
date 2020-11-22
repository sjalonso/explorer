<?php

namespace App\Controller;

use App\Entity\DiaVuelta;
use App\Form\DiaVueltaType;
use App\Repository\DiaVueltaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/dia/vuelta")
 */
class DiaVueltaController extends AbstractController
{
    /**
     * @Route("/", name="dia_vuelta_index", methods={"GET"})
     */
    public function index(DiaVueltaRepository $diaVueltaRepository): Response
    {
        return $this->render('dia_vuelta/index.html.twig', [
            'dia_vueltas' => $diaVueltaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="dia_vuelta_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $diaVueltum = new DiaVuelta();
        $form = $this->createForm(DiaVueltaType::class, $diaVueltum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($diaVueltum);
            $entityManager->flush();

            return $this->redirectToRoute('dia_vuelta_index');
        }

        return $this->render('dia_vuelta/new.html.twig', [
            'dia_vueltum' => $diaVueltum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dia_vuelta_show", methods={"GET"})
     */
    public function show(DiaVuelta $diaVueltum): Response
    {
        return $this->render('dia_vuelta/show.html.twig', [
            'dia_vueltum' => $diaVueltum,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="dia_vuelta_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DiaVuelta $diaVueltum): Response
    {
        $form = $this->createForm(DiaVueltaType::class, $diaVueltum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('dia_vuelta_index', [
                'id' => $diaVueltum->getId(),
            ]);
        }

        return $this->render('dia_vuelta/edit.html.twig', [
            'dia_vueltum' => $diaVueltum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="dia_vuelta_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DiaVuelta $diaVueltum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$diaVueltum->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($diaVueltum);
            $entityManager->flush();
        }

        return $this->redirectToRoute('dia_vuelta_index');
    }
}
