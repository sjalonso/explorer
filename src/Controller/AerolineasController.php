<?php

namespace App\Controller;

use App\Entity\Aerolineas;
use App\Form\AerolineasType;
use App\Repository\AerolineasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/aerolineas")
 */
class AerolineasController extends AbstractController
{
    /**
     * @Route("/", name="aerolineas_index", methods={"GET"})
     */
    public function index(Request $request, AerolineasRepository $aerolineasRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        return $this->render('aerolineas/index.html.twig', [
            'aerolineas' => $aerolineasRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="aerolineas_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $aerolinea = new Aerolineas();
        $form = $this->createForm(AerolineasType::class, $aerolinea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($aerolinea);
            $entityManager->flush();

            return $this->redirectToRoute('aerolineas_index');
        }

        return $this->render('aerolineas/new.html.twig', [
            'aerolinea' => $aerolinea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="aerolineas_show", methods={"GET"})
     */
    public function show(Request $request, Aerolineas $aerolinea): Response
    {
        $session = $request->getSession();
        $session->set('delete_aerolinea', "show");

        return $this->render('aerolineas/show.html.twig', [
            'aerolinea' => $aerolinea,
            'error' => ''
        ]);
    }

    /**
     * @Route("/{id}/edit", name="aerolineas_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Aerolineas $aerolinea): Response
    {
        $session = $request->getSession();
        $session->set('delete_aerolinea', "edit");
        $msg = $session->get('delete_aerolinea_msg');
        $session->set('delete_aerolinea_msg', "");

        $form = $this->createForm(AerolineasType::class, $aerolinea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('aerolineas_index', [
                'id' => $aerolinea->getId(),
            ]);
        }

        return $this->render('aerolineas/edit.html.twig', [
            'aerolinea' => $aerolinea,
            'form' => $form->createView(),
            'error' => $msg
        ]);
    }

    /**
     * @Route("/{id}", name="aerolineas_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Aerolineas $aerolinea): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aerolinea->getId(), $request->request->get('_token'))) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($aerolinea);
                $entityManager->flush();
            }
            catch(\Exception $e){
                $msg = "No se puede eliminar esta aerolínea";
                $pos = strpos($e->getMessage(), "Integrity constraint violation");
                if($pos !== false)
                    $msg .= " por estar relacionada con otros registros";

                $session = $request->getSession();
                $mostrar = $session->get('delete_aerolinea');
                $session->set('delete_aerolinea', "");
                if($mostrar == "show") {
                    return $this->render('aerolineas/show.html.twig', [
                        'aerolinea' => $aerolinea,
                        'error' => $msg
                    ]);
                }
                else if($mostrar == "edit"){
                    $session->set('delete_aerolinea_msg', $msg);
                    return $this->redirectToRoute('aerolineas_edit', [
                        'id' => $aerolinea->getId(),
                    ]);
                }
                else
                    return $this->redirectToRoute('aerolineas_index');
            }
        }

        return $this->redirectToRoute('aerolineas_index');
    }
}
