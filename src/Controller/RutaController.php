<?php

namespace App\Controller;
use App\Entity\Areopuerto;
use App\Entity\Ruta;
use App\Form\RutaType;
use App\Repository\RutaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ruta")
 */
class RutaController extends AbstractController
{
    /**
     * @Route("/", name="ruta_index", methods={"GET"})
     */
    public function index(Request $request, RutaRepository $rutaRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        return $this->render('ruta/index.html.twig', [
            'rutas' => $rutaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ruta_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $rutum = new Ruta();
        $form = $this->createForm(RutaType::class, $rutum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($rutum);
            $entityManager->flush();

            return $this->redirectToRoute('ruta_index');
        }

        return $this->render('ruta/new.html.twig', [
            'rutum' => $rutum,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="ruta_show", methods={"GET"})
     */
    public function show(Request $request, Ruta $rutum): Response
    {
        $session = $request->getSession();
        $session->set('delete_ruta', "show");

        return $this->render('ruta/show.html.twig', [
            'rutum' => $rutum,
            'error' => '',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ruta_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ruta $rutum): Response
    {
        $session = $request->getSession();
        $session->set('delete_ruta', "edit");
        $msg = $session->get('delete_ruta_msg');
        $session->set('delete_ruta_msg', "");
        $form = $this->createForm(RutaType::class, $rutum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('ruta_index', [
                'id' => $rutum->getId(),
            ]);
        }

        return $this->render('ruta/edit.html.twig', [
            'rutum' => $rutum,
            'form' => $form->createView(),
            'error' => $msg
        ]);
    }

    /**
     * @Route("/{id}", name="ruta_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ruta $rutum): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rutum->getId(), $request->request->get('_token'))) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($rutum);
                $entityManager->flush();
            }
            catch(\Exception $e){
                $msg = "No se puede eliminar esta ruta";
                $pos = strpos($e->getMessage(), "Integrity constraint violation");
                if($pos !== false)
                    $msg .= " por estar relacionada con otros registros";

                $session = $request->getSession();
                $mostrar = $session->get('delete_ruta');
                $session->set('delete_ruta', "");
                if($mostrar == "show"){
                    return $this->render('ruta/show.html.twig', [
                        'rutum' => $rutum,
                        'error' => $msg
                    ]);
                }
                else if($mostrar == "edit"){
                    $session->set('delete_ruta_msg', $msg);
                    return $this->redirectToRoute('ruta_edit', [
                        'id' => $rutum->getId(),
                    ]);
                }
                else
                    return $this->redirectToRoute('ruta_index');

            }
        }

        return $this->redirectToRoute('ruta_index');
    }
}
