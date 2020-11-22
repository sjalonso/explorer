<?php

namespace App\Controller;

use App\Entity\Cliente;
use App\Entity\Sucursal;
use App\Entity\Sexo;
use App\Entity\Titulo;
use App\Form\ClienteType;
use App\Repository\ClienteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cliente")
 */
class ClienteController extends AbstractController
{
    /**
     * @Route("/", name="cliente_index", methods={"GET"})
     */
    public function index(Request $request, ClienteRepository $clienteRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechaida_reserva', "");
        $session->set('fecharegreso_reserva', "");
        $session->set('ruta_reserva', "");
        $session->set('estado_reserva', "");
        $session->set('estado_receptor_reserva', "");
        $session->set('sucursal_reserva', "");

        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        $listado = array();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $sucursal = $user->getSucursal();
        if($sucursal) {
            $id = $sucursal->getId();
            $iduser = $user->getId();
            $listado = $clienteRepository->findBySucursal($id);
            if ($this->isGranted('ROLE_USER'))
                $listado = $clienteRepository->findBy(array('sucursal'=>$id, 'user'=>$iduser));
        }
        else
           $listado = $clienteRepository->findAll();
        if ($this->isGranted('ROLE_ADMIN'))
            $listado = $clienteRepository->findAll();

        return $this->render('cliente/index.html.twig', [
            'clientes' => $listado,
        ]);
    }

    /**
     * @Route("/new", name="cliente_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cliente = new Cliente();
        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Capturar usuario logueado y asignar sucursal al cliente
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $sucursal = $user->getSucursal();
            if($sucursal)
                $cliente->setSucursal($sucursal);
            if ($this->isGranted('ROLE_USER'))
                $cliente->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cliente);
            $entityManager->flush();

          

            return $this->redirectToRoute('cliente_index');
        }

        return $this->render('cliente/new.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cliente_show", methods={"GET"})
     */
    public function show(Request $request, Cliente $cliente): Response
    {
        $session = $request->getSession();
        $session->set('delete_cliente', "show");

        return $this->render('cliente/show.html.twig', [
            'cliente' => $cliente,
            'error' => ''
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cliente_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cliente $cliente): Response
    {
        $session = $request->getSession();
        $session->set('delete_cliente', "edit");
        $msg = $session->get('delete_cliente_msg');
        $session->set('delete_cliente_msg', "");

        $form = $this->createForm(ClienteType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cliente_index', [
                'id' => $cliente->getId(),
            ]);
        }

        return $this->render('cliente/edit.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
            'error' => $msg
        ]);
    }

    /**
     * @Route("/{id}", name="cliente_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cliente $cliente): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cliente->getId(), $request->request->get('_token'))) {
            try {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($cliente);
                $entityManager->flush();
            }
            catch(\Exception $e){
                $msg = "No se puede eliminar este cliente";
                $pos = strpos($e->getMessage(), "Integrity constraint violation");
                if($pos !== false)
                    $msg .= " por estar relacionado con otros registros";

                $session = $request->getSession();
                $mostrar = $session->get('delete_cliente');
                $session->set('delete_cliente', "");
                if($mostrar == "show") {
                    return $this->render('cliente/show.html.twig', [
                        'cliente' => $cliente,
                        'error' => $msg
                    ]);
                }
                else if($mostrar == "edit"){
                    $sexo = $cliente->getSexo();
                    if($sexo) {
                        $idsexo = $sexo->getId();
                        $sexo = $entityManager->getRepository(Sexo::class)->find($idsexo);
                        $cliente->setSexo($sexo);
                    }
                    $titulo = $cliente->getTitulo();
                    if($titulo) {
                        $idtitulo = $titulo->getId();
                        $titulo = $entityManager->getRepository(Titulo::class)->find($idtitulo);
                        $cliente->setTitulo($titulo);
                    }
                    $session->set('delete_cliente_msg', $msg);
                    return $this->redirectToRoute('cliente_edit', [
                        'id' => $cliente->getId(),
                    ]);
                }
                else
                    return $this->redirectToRoute('cliente_index');
            }
        }

        return $this->redirectToRoute('cliente_index');
    }
}
