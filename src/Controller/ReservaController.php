<?php

namespace App\Controller;

use App\Entity\EstadoReserva;
use App\Entity\Reserva;
use App\Entity\Paquete;
use App\Entity\Ruta;
use App\Entity\SolicitudVisa;
use App\Entity\Sucursal;
use App\Form\ReservaType;
use App\Form\ReservaType2;
use App\Repository\ReservaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/reserva")
 */
class ReservaController extends AbstractController
{
    /**
     * @Route("/", name="reserva_index", methods={"GET"})
     */
    public function index(Request $request, ReservaRepository $reservaRepository): Response
    {
        $session = $request->getSession();
        $fechaida = $session->get('fechaida_reserva');
        $fecharegreso = $session->get('fecharegreso_reserva');
        $ruta = $session->get('ruta_reserva');
        $estado = $session->get('estado_reserva');
        $estado_receptor = $session->get('estado_receptor_reserva');
        $sucursal_filtro = $session->get('sucursal_reserva');

        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");
        $session->set('infante', "");

        $listado = array();
        $entityManager = $this->getDoctrine()->getManager();
        $rutas = $entityManager->getRepository(Ruta::class)->findAll();
        $estados = $entityManager->getRepository(EstadoReserva::class)->findAll();
        $sucursales = $entityManager->getRepository(Sucursal::class)->findAll();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $sucursal = $user->getSucursal();
        if($sucursal) {
            $id = $sucursal->getId();
            $listado = $reservaRepository->findBySucursal($id);
            //Si el usuario es COMERCIAL buscar solo las reservas hechas por él
            if($this->isGranted('ROLE_USER')){
                $iduser = $user->getId();
                $listado = $reservaRepository->findBySucursalAndUser($id, $iduser);
            }
        }
        else
            $listado = $reservaRepository->findAll();
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_RECEPTOR'))
            $listado = $reservaRepository->findAll();

        return $this->render('reserva/index.html.twig', [
            'reservas' => $listado,
            'rutas' => $rutas,
            'estados' => $estados,
            'sucursales' => $sucursales,
            'fechaida' => $fechaida,
            'fecharegreso' => $fecharegreso,
            'ruta_reserva' => $ruta,
            'estado_reserva' => $estado,
            'estado_receptor_reserva' => $estado_receptor,
            'sucursal_reserva' => $sucursal_filtro
        ]);
    }

   /**
     * @Route("/new/{id_paquete}", name="reserva_new", methods={"GET","POST"})
     */
    public function new(Request $request, $id_paquete): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $paq = $entityManager->getRepository(Paquete::class)->find($id_paquete);
        $codigopaquete = $paq->getCodigopaquete();
        //Buscar clientes que pertenezcan a la sucursal del usuario
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $idsuc = null;
        $iduser = null;
        if($user && $user->getSucursal() && !$this->isGranted('ROLE_ADMIN'))
            $idsuc = $user->getSucursal()->getId();
        if($user && $user->getSucursal() && $this->isGranted('ROLE_USER'))
            $iduser = $user->getId();

        $reserva = new Reserva($paq);        
        
        $form = $this->createForm(ReservaType::class, $reserva, [
                'sucursal' => $idsuc,
                'usuario' => $iduser,
            ]);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $error = false;
            $msg = "";
            $idcliente = $reserva->getCliente()->getId();
            $resXcliente = $entityManager->getRepository(Reserva::class)
                ->findOneBy(array('paquete'=>$id_paquete, 'Cliente'=>$idcliente));
            if($paq->getCantasiento() == 0){
                $error = true;
                $msg = "No se pueden registrar reservas en este paquete pues se encuentra agotado";
            }
            else if($resXcliente){
                $error = true;
                $msg = "No se pueden registrar reservas en este paquete para este cliente";
            }
            if(!$error) {
                if(!$reserva->getInfante())
                    $paq->setCantasiento($paq->getCantasiento() - 1);

                $reserva->setPaquete($paq);
                $entityManager->persist($paq);
                $entityManager->flush();
                //asignar usuario y estado receptor
                $reserva->setUser($user);
                $reserva->setEstadoreceptor("No Asignado");
                //asignar estado reserva
                if($reserva->getTipopaquete() == "Paquete Normal" || $reserva->getTipopaquete() == "Mini paquete con visa")
                    $estadoReserva = $entityManager->getRepository(EstadoReserva::class)
                        ->findOneByTipoestado("Listo");
                else
                    $estadoReserva = $entityManager->getRepository(EstadoReserva::class)
                        ->findOneByTipoestado("En espera Visa");
                $reserva->setEstadoReserva($estadoReserva);

                $entityManager->persist($reserva);
                $entityManager->flush();
                if($reserva->getEstadoReserva() == "En espera Visa")
                    return $this->redirectToRoute('solicitud_visa_new2');
                else
                    return $this->redirectToRoute('paquete_index');
            }
            return $this->render('reserva/new.html.twig', [
                'reserva' => $reserva,
                'error' => $msg,
                'form' => $form->createView(),
                'codigopaquete' => $codigopaquete
            ]);
        }

        return $this->render('reserva/new.html.twig', [
            'reserva' => $reserva,
            'error' => '',
            'form' => $form->createView(),
            'codigopaquete' => $codigopaquete
        ]);
    }

    /**
     * @Route("/printcomprobante/{id}", name="imprimefactura_show", methods={"GET"})
     */

    public function imprimefacturashow(Reserva $reserva): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reserva/printcomprobante.html.twig', [
            'reserva' => $reserva,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);

        $dompdf->set_base_path("/www/public/assets-conf/");
    }


    /**
     * @Route("/{id}", name="reserva_show", methods={"GET"})
     */
    public function show(Reserva $reserva): Response
    {
        return $this->render('reserva/show.html.twig', [
            'reserva' => $reserva,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="reserva_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reserva $reserva): Response
    {
        //Buscar clientes que pertenezcan a la sucursal del usuario
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $idsuc = null;
        $iduser = null;
        if($user && $user->getSucursal() && !$this->isGranted('ROLE_ADMIN'))
            $idsuc = $user->getSucursal()->getId();
        if($user && $user->getSucursal() && $this->isGranted('ROLE_USER'))
            $iduser = $user->getId();

        $entityManager = $this->getDoctrine()->getManager();
        $idpaquete = $reserva->getPaquete()->getId();
        $paquete = $entityManager->getRepository(Paquete::class)->find($idpaquete);
        $codigopaquete = $paquete->getCodigopaquete();
        $form = $this->createForm(ReservaType::class, $reserva, array('sucursal' => $idsuc, 'usuario' => $iduser));
        $form->handleRequest($request);
        //Obtener campo infante sin modificar
        $session = $request->getSession();
        $infante = $session->get('infante');
        if($infante == "") {
            if ($reserva->getInfante())
                $session->set('infante', "si");
            else
                $session->set('infante', "no");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('infante', "");
            $infanteCambiado = "";
            if ($reserva->getInfante())
                $infanteCambiado = "si";
            else
                $infanteCambiado = "no";
            $msg = "";
            if($infanteCambiado != $infante){
                $cantAsientos = $paquete->getCantasiento();
                if(!$reserva->getInfante() && $cantAsientos == 0)
                    $msg = "No se puede modificar el campo Infante por no existir disponibilidad en el paquete";
                else {
                    if (!$reserva->getInfante() && $cantAsientos > 0)
                        $paquete->setCantasiento($cantAsientos - 1);
                    if($reserva->getInfante())
                        $paquete->setCantasiento($cantAsientos + 1);

                    $entityManager->persist($paquete);
                }
            }
            if($msg == "") {
                //asignar usuario
                $reserva->setUser($user);
                $entityManager->flush();
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('reserva_index', [
                    'id' => $reserva->getId(),
                ]);
            }
            else
                return $this->render('reserva/edit.html.twig', [
                    'reserva' => $reserva,
                    'error' => $msg,
                    'form' => $form->createView(),
                    'codigopaquete' => $codigopaquete

                ]);
        }

        return $this->render('reserva/edit.html.twig', [
            'reserva' => $reserva,
            'error' => '',
            'form' => $form->createView(),
            'codigopaquete' => $codigopaquete
        ]);
    }

     /**
     * @Route("/{id}/edit2", name="reserva_edit2", methods={"GET","POST"})
     */
    public function edit2(Request $request, Reserva $reserva): Response
    {
        //Buscar clientes que pertenezcan a la sucursal del usuario
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $idsuc = null;
        if($user && $user->getSucursal() && !$this->isGranted('ROLE_ADMIN'))
            $idsuc = $user->getSucursal()->getId();

        $entityManager = $this->getDoctrine()->getManager();
        $idpaquete = $reserva->getPaquete()->getId();
        $paquete = $entityManager->getRepository(Paquete::class)->find($idpaquete);
        $codigopaquete = $paquete->getCodigopaquete();
        $form = $this->createForm(ReservaType2::class, $reserva, array('sucursal' => $idsuc));
        $form->handleRequest($request);
        //Obtener campo infante sin modificar
        $session = $request->getSession();
        $infante = $session->get('infante');
        if($infante == "") {
            if ($reserva->getInfante())
                $session->set('infante', "si");
            else
                $session->set('infante', "no");
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $session->set('infante', "");
            $infanteCambiado = "";
            if ($reserva->getInfante())
                $infanteCambiado = "si";
            else
                $infanteCambiado = "no";
            $msg = "";
            if($infanteCambiado != $infante){
                $cantAsientos = $paquete->getCantasiento();
                if(!$reserva->getInfante() && $cantAsientos == 0)
                    $msg = "No se puede modificar el campo Infante por no existir disponibilidad en el paquete";
                else {
                    if (!$reserva->getInfante() && $cantAsientos > 0)
                        $paquete->setCantasiento($cantAsientos - 1);
                    if($reserva->getInfante())
                        $paquete->setCantasiento($cantAsientos + 1);

                    $entityManager->persist($paquete);
                }
            }
            if($msg == "") {
                //asignar usuario
                $reserva->setUser($user);
                $entityManager->flush();
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('reserva_index', [
                    'id' => $reserva->getId(),
                ]);
            }
            else
                return $this->render('reserva/edit.html.twig', [
                    'reserva' => $reserva,
                    'error' => $msg,
                    'form' => $form->createView(),
                    'codigopaquete' => $codigopaquete
                ]);
        }

        return $this->render('reserva/edit.html.twig', [
            'reserva' => $reserva,
            'error' => '',
            'form' => $form->createView(),
            'codigopaquete' => $codigopaquete
        ]);
    }

    /**
     * @Route("/{id}", name="reserva_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Reserva $reserva): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reserva->getId(), $request->request->get('_token'))) {
            //Incrementar cant asientos en paquete asociado
            $id_paquete = $reserva->getPaquete()->getId();
            $entityManager = $this->getDoctrine()->getManager();
            $paq = $entityManager->getRepository(Paquete::class)->find($id_paquete);
            if(!$reserva->getInfante()) {
                $paq->setCantasiento($paq->getCantasiento() + 1);
                $entityManager->persist($paq);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reserva);
            $entityManager->flush();
        }

        return $this->redirectToRoute('reserva_index');
    }

        /**
     * @Route("/{id}/estado", name="reserva_receptor_estado", methods={"GET", "POST"})
     */
    public function estado(Request $request, Reserva $reserva): Response
    {
        $estado = $request->request->get('estado');
        $entityManager = $this->getDoctrine()->getManager();
        $reserva->setEstadoreceptor($estado);
        $entityManager->persist($reserva);
        $entityManager->flush();

        return $this->redirectToRoute('reserva_index');
    }

         /**
     * @Route("/{id}/estado", name="reserva_receptor_estado2", methods={"GET", "POST"})
     */
    public function estado1(Request $request, Reserva $reserva): Response
    {
        $estado = $request->request->get('estado');
        $entityManager = $this->getDoctrine()->getManager();
        $estadoSolicitud = $entityManager->getRepository(Reserva::class)->findOneByEstadoreceptor($estado);
        $reserva->setEstadoreceptor($estado);
        $entityManager->persist($reserva);
        $entityManager->flush();

        return $this->redirectToRoute('reserva_index');
    }

    /**
     * @Route("/setearfiltroreserva", name="setearfiltroreserva")
     */
    public function setearfiltroreserva(Request $request)
    {
        $session = $request->getSession();
        $fechaida = $request->get('fechaida');
        $fecharegreso = $request->get('fecharegreso');
        $ruta = $request->get('ruta');
        $estado = $request->get('estado');
        $estado_receptor = $request->get('estado_receptor');
        $sucursal = $request->get('sucursal');
        $session->set('fechaida_reserva', $fechaida);
        $session->set('fecharegreso_reserva', $fecharegreso);
        $session->set('ruta_reserva', $ruta);
        $session->set('estado_reserva', $estado);
        $session->set('estado_receptor_reserva', $estado_receptor);
        $session->set('sucursal_reserva', $sucursal);
        $respuesta = new Response();
        $respuesta->headers->set('Content-Type', 'text');
        $respuesta->setStatusCode(200);
        $respuesta->setContent("OK");
        return $respuesta;
    }   
    
  

}
