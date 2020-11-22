<?php

namespace App\Controller;

use App\Entity\Titulo;
use App\Form\TituloType;
use App\Repository\TituloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/titulo")
 */
class TituloController extends AbstractController
{
    /**
     * @Route("/", name="titulo_index", methods={"GET"})
     */
    public function index(Request $request, TituloRepository $tituloRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        return $this->render('titulo/index.html.twig', [
            'titulos' => $tituloRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="titulo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $titulo = new Titulo();
        $form = $this->createForm(TituloType::class, $titulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($titulo);
            $entityManager->flush();

            return $this->redirectToRoute('titulo_index');
        }

        return $this->render('titulo/new.html.twig', [
            'titulo' => $titulo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="titulo_show", methods={"GET"})
     */
    public function show(Request $request, Titulo $titulo): Response
    {
        $session = $request->getSession();
        $session->set('delete_titulo', "show");

        return $this->render('titulo/show.html.twig', [
            'titulo' => $titulo,
            'error' => ''
        ]);
    }

    /**
     * @Route("/{id}/edit", name="titulo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Titulo $titulo): Response
    {
        $session = $request->getSession();
        $session->set('delete_titulo', "edit");
        $msg = $session->get('delete_titulo_msg');
        $session->set('delete_titulo_msg', "");
        
        $form = $this->createForm(TituloType::class, $titulo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('titulo_index', [
                'id' => $titulo->getId(),
            ]);
        }

        return $this->render('titulo/edit.html.twig', [
            'titulo' => $titulo,
            'form' => $form->createView(),
            'error' => $msg
        ]);
    }

    /**
     * @Route("/{id}", name="titulo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Titulo $titulo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$titulo->getId(), $request->request->get('_token'))) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($titulo);
                $entityManager->flush();
            }
            catch(\Exception $e){
                $msg = "No se puede eliminar este título";
                $pos = strpos($e->getMessage(), "Integrity constraint violation");
                if($pos !== false)
                    $msg .= " por estar relacionado con otros registros";

                $session = $request->getSession();
                $mostrar = $session->get('delete_titulo');
                $session->set('delete_titulo', "");
                if($mostrar == "show"){
                    return $this->render('titulo/show.html.twig', [
                        'titulo' => $titulo,
                        'error' => $msg
                    ]);
                }
                else if($mostrar == "edit"){
                    $session->set('delete_titulo_msg', $msg);
                    return $this->redirectToRoute('titulo_edit', [
                        'id' => $titulo->getId(),
                    ]);
                }
                else
                    return $this->redirectToRoute('titulo_index');
            }
        }

        return $this->redirectToRoute('titulo_index');
    }
}
