<?php

namespace App\Controller;

use App\Entity\Sucursal;
use App\Form\SucursalType;
use App\Repository\SucursalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sucursal")
 */
class SucursalController extends AbstractController
{
    /**
     * @Route("/", name="sucursal_index", methods={"GET"})
     */
    public function index(Request $request, SucursalRepository $sucursalRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        return $this->render('sucursal/index.html.twig', [
            'sucursals' => $sucursalRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="sucursal_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $sucursal = new Sucursal();
        $form = $this->createForm(SucursalType::class, $sucursal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($sucursal);
            $entityManager->flush();

            return $this->redirectToRoute('sucursal_index');
        }

        return $this->render('sucursal/new.html.twig', [
            'sucursal' => $sucursal,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sucursal_show", methods={"GET"})
     */
    public function show(Request $request, Sucursal $sucursal): Response
    {
        $session = $request->getSession();
        $session->set('delete_sucursal', "show");

        return $this->render('sucursal/show.html.twig', [
            'sucursal' => $sucursal,
            'error' => '',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sucursal_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Sucursal $sucursal): Response
    {
        $session = $request->getSession();
        $session->set('delete_sucursal', "edit");
        $msg = $session->get('delete_sucursal_msg');
        $session->set('delete_sucursal_msg', "");

        $form = $this->createForm(SucursalType::class, $sucursal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sucursal_index', [
                'id' => $sucursal->getId(),
            ]);
        }

        return $this->render('sucursal/edit.html.twig', [
            'sucursal' => $sucursal,
            'form' => $form->createView(),
            'error' => $msg
        ]);
    }

    /**
     * @Route("/{id}", name="sucursal_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Sucursal $sucursal): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sucursal->getId(), $request->request->get('_token'))) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($sucursal);
                $entityManager->flush();
            }
            catch(\Exception $e){
                $msg = "No se puede eliminar esta sucursal";
                $pos = strpos($e->getMessage(), "Integrity constraint violation");
                if($pos !== false)
                    $msg .= " por estar relacionado con otros registros";

                $session = $request->getSession();
                $mostrar = $session->get('delete_sucursal');
                $session->set('delete_sucursal', "");
                if($mostrar == "show"){
                    return $this->render('sucursal/show.html.twig', [
                        'rutum' => $sucursal,
                        'error' => $msg
                    ]);
                }
                else if($mostrar == "edit"){
                    $session->set('delete_sucursal_msg', $msg);
                    return $this->redirectToRoute('sucursal_edit', [
                        'id' => $sucursal->getId(),
                    ]);
                }
                else
                    return $this->redirectToRoute('sucursal_index');
            }
        }

        return $this->redirectToRoute('sucursal_index');
    }
}
