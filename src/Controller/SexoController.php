<?php

namespace App\Controller;

use App\Entity\Sexo;
use App\Form\SexoType;
use App\Repository\SexoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sexo")
 */
class SexoController extends AbstractController
{
    /**
     * @Route("/", name="sexo_index", methods={"GET"})
     */
    public function index(Request $request, SexoRepository $sexoRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        return $this->render('sexo/index.html.twig', [
            'sexos' => $sexoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sexo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sexo = new Sexo();
        $form = $this->createForm(SexoType::class, $sexo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sexo);
            $entityManager->flush();

            return $this->redirectToRoute('sexo_index');
        }

        return $this->render('sexo/new.html.twig', [
            'sexo' => $sexo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sexo_show", methods={"GET"})
     */
    public function show(Request $request, Sexo $sexo): Response
    {
        $session = $request->getSession();
        $session->set('delete_sexo', "show");

        return $this->render('sexo/show.html.twig', [
            'sexo' => $sexo,
            'error' => ''
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sexo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sexo $sexo): Response
    {
        $session = $request->getSession();
        $session->set('delete_sexo', "edit");
        $msg = $session->get('delete_sexo_msg');
        $session->set('delete_sexo_msg', "");
        $form = $this->createForm(SexoType::class, $sexo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sexo_index', [
                'id' => $sexo->getId(),
            ]);
        }

        return $this->render('sexo/edit.html.twig', [
            'sexo' => $sexo,
            'form' => $form->createView(),
            'error' => $msg
        ]);
    }

    /**
     * @Route("/{id}", name="sexo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sexo $sexo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sexo->getId(), $request->request->get('_token'))) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($sexo);
                $entityManager->flush();
            }
            catch(\Exception $e){
                $msg = "No se puede eliminar este sexo";
                $pos = strpos($e->getMessage(), "Integrity constraint violation");
                if($pos !== false)
                    $msg .= " por estar relacionado con otros registros";

                $session = $request->getSession();
                $mostrar = $session->get('delete_sexo');
                $session->set('delete_sexo', "");
                if($mostrar == "show"){
                    return $this->render('sexo/show.html.twig', [
                        'sexo' => $sexo,
                        'error' => $msg
                    ]);
                }
                else if($mostrar == "edit"){
                    $session->set('delete_sexo_msg', $msg);
                    return $this->redirectToRoute('sexo_edit', [
                        'id' => $sexo->getId(),
                    ]);
                }
                else
                    return $this->redirectToRoute('sexo_index');
            }
        }

        return $this->redirectToRoute('sexo_index');
    }
}
