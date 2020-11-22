<?php

namespace App\Controller;

use App\Entity\EstadoSolicitudvisa;
use App\Entity\SolicitudVisa;

use App\Entity\Sucursal;
use App\Form\SolicitudVisaType;
use App\Form\SolicitudVisaType2;
use App\Form\SolicitudVisaType3;
use App\Repository\SolicitudVisaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/solicitud/visa")
 */
class SolicitudVisaController extends AbstractController
{
    /**
     * @Route("/", name="solicitud_visa_index", methods={"GET"})
     */
    public function index(Request $request, SolicitudVisaRepository $solicitudVisaRepository): Response
    {
        $session = $request->getSession();
        $fechainicio = $session->get('fechainicio_solicitud');
        $estado = $session->get('estado_solicitud');
        $entityManager = $this->getDoctrine()->getManager();
        $sucursales = $entityManager->getRepository(Sucursal::class)->findAll();

        $listado = array();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        
      
        $sucursal = $user->getSucursal();
        if($sucursal) {
            $id = $sucursal->getId();
            $listado = $solicitudVisaRepository->findBySucursal($id);
            //Si el usuario es COMERCIAL buscar solo las solicitudes hechas por él
            if($this->isGranted('ROLE_USER')){
                $iduser = $user->getId();
                $listado = $solicitudVisaRepository->findBySucursalAndUser($id, $iduser);
            }
        }
        else
            $listado = $solicitudVisaRepository->findAll();
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_VISADO'))
            $listado = $solicitudVisaRepository->findAll();

        return $this->render('solicitud_visa/index.html.twig', [
            'solicitud_visas' => $listado,
            'fechainicio' => $fechainicio,
            'estado_solicitud' => $estado,
            'sucursales' => $sucursales
        ]);
    }

    /**
     * @Route("/reporte", name="solicitud_visa_reporte", methods={"GET"})
     */
    public function reporte(Request $request, SolicitudVisaRepository $solicitudVisaRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        $listado = array();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $sucursal = $user->getSucursal();
        if($sucursal) {
            $id = $sucursal->getId();
            $listado = $solicitudVisaRepository->findBySucursal($id);
        }
        else
            $listado = $solicitudVisaRepository->findAll();
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_VISADO'))
            $listado = $solicitudVisaRepository->findAll();

        return $this->render('solicitud_visa/reporte.html.twig', [
            'solicitud_visas' => $listado,
        ]);
    }

    /**
     * @Route("/new", name="solicitud_visa_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $suc = "";
        $iduser = "";
        if($user->getSucursal() && !$this->isGranted('ROLE_ADMIN'))
            $suc = $user->getSucursal()->getId();
        if($user->getSucursal() && $this->isGranted('ROLE_USER'))
            $iduser = $user->getId();

        $solicitudVisa = new SolicitudVisa();
        $form = $this->createForm(SolicitudVisaType2::class, $solicitudVisa,
            array('sucursal' => $suc, 'usuario' => $iduser));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $estado = $entityManager->getRepository(EstadoSolicitudvisa::class)->find(1);
            //asignar usuario
            $solicitudVisa->setUser($user);
            $solicitudVisa->setEstadoSolicitudvisa($estado);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($solicitudVisa);
            $entityManager->flush();

            return $this->redirectToRoute('solicitud_visa_index');
        }

        return $this->render('solicitud_visa/new.html.twig', [
            'solicitud_visa' => $solicitudVisa,
            'form' => $form->createView(),
        ]);
    }

        /**
     * @Route("/new2", name="solicitud_visa_new2", methods={"GET","POST"})
     */
    public function new2(Request $request): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $suc = "";
        $iduser = "";
        if($user->getSucursal() && !$this->isGranted('ROLE_ADMIN'))
            $suc = $user->getSucursal()->getId();
        if($user->getSucursal() && $this->isGranted('ROLE_USER'))
            $iduser = $user->getId();

        $solicitudVisa = new SolicitudVisa();
        $form = $this->createForm(SolicitudVisaType2::class, $solicitudVisa,
            array('sucursal' => $suc, 'usuario' => $iduser));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $estado = $entityManager->getRepository(EstadoSolicitudvisa::class)->find(1);
            //asignar usuario
            
            $solicitudVisa->setUser($user);
            $solicitudVisa->setEstadoSolicitudvisa($estado);
           
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($solicitudVisa);
            $entityManager->flush();

            return $this->redirectToRoute('solicitud_visa_index');
        }

        return $this->render('solicitud_visa/new.html.twig', [
            'solicitud_visa' => $solicitudVisa,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="solicitud_visa_show", methods={"GET"})
     */
    public function show(SolicitudVisa $solicitudVisa): Response
    {
        return $this->render('solicitud_visa/show.html.twig', [
            'solicitud_visa' => $solicitudVisa,
            'retorno' => 'index',
        ]);
    }

    /**
     * @Route("/{id}/rep", name="solicitud_visa_show_rep", methods={"GET"})
     */
    public function showrep(SolicitudVisa $solicitudVisa): Response
    {
        return $this->render('solicitud_visa/show.html.twig', [
            'solicitud_visa' => $solicitudVisa,
            'retorno' => 'reporte',
        ]);
    }

      /**
     * @Route("show/{id}", name="solicitud_visa_show2", methods={"GET"})
     */
    public function show2(SolicitudVisa $solicitudVisa): Response
    {
        return $this->render('solicitud_visa/show2.html.twig', [
            'solicitud_visa' => $solicitudVisa,
            'retorno' => 'index',
        ]);
    }

    /**
     * @Route("/printsolicitud/{id}", name="printsolicitudvisa_show", methods={"GET"})
     */
    public function imprimeshow(SolicitudVisa $solicitudVisa): Response
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('solicitud_visa/printsolicitud.html.twig', [
            'solicitud_visa' => $solicitudVisa,
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
     * @Route("/{id}/edit", name="solicitud_visa_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SolicitudVisa $solicitudVisa): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $sucursal = "";
        if($user->getSucursal() && !$this->isGranted('ROLE_ADMIN'))
            $sucursal = $user->getSucursal()->getId();
        $form = $this->createForm(SolicitudVisaType3::class, $solicitudVisa, array('sucursal' => $sucursal));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //asignar usuario
            $solicitudVisa->setUser($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('solicitud_visa_index', [
                'id' => $solicitudVisa->getId(),
            ]);
        }

        return $this->render('solicitud_visa/edit.html.twig', [
            'solicitud_visa' => $solicitudVisa,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit2", name="solicitud_visa_edit2", methods={"GET","POST"})
     */
    public function edit2(Request $request, SolicitudVisa $solicitudVisa): Response
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $sucursal = "";
        $iduser = "";
        if($user->getSucursal() && !$this->isGranted('ROLE_ADMIN'))
            $sucursal = $user->getSucursal()->getId();
        if($user->getSucursal() && $this->isGranted('ROLE_USER'))
            $iduser = $user->getId();
        $form = $this->createForm(SolicitudVisaType::class, $solicitudVisa,
            array('sucursal' => $sucursal, 'usuario' => $iduser));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //asignar usuario
            $solicitudVisa->setUser($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('solicitud_visa_index', [
                'id' => $solicitudVisa->getId(),
            ]);
        }

        return $this->render('solicitud_visa/edit.html.twig', [
            'solicitud_visa' => $solicitudVisa,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/estado", name="solicitud_visa_estado", methods={"GET", "POST"})
     */
    public function estado(Request $request, SolicitudVisa $solicitudVisa): Response
    {
        $estado = $request->request->get('estado');
        $entityManager = $this->getDoctrine()->getManager();
        $estadoSolicitud = $entityManager->getRepository(EstadoSolicitudvisa::class)->findOneByTipoestado($estado);
        $solicitudVisa->setEstadoSolicitudvisa($estadoSolicitud);
        $entityManager->persist($solicitudVisa);
        $entityManager->flush();

        return $this->redirectToRoute('solicitud_visa_index');
    }

    /**
     * @Route("/{id}", name="solicitud_visa_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SolicitudVisa $solicitudVisa): Response
    {
        if ($this->isCsrfTokenValid('delete'.$solicitudVisa->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($solicitudVisa);
            $entityManager->flush();
        }

        return $this->redirectToRoute('solicitud_visa_index');
    }

    /**
     * @Route("/setearfiltrosolicitudvisa", name="setearfiltrosolicitudvisa")
     */
    public function setearfiltroreserva(Request $request)
    {
        $session = $request->getSession();
        $fechainicio = $request->get('fechainicio');
        $estado = $request->get('estado');
        $session->set('fechainicio_solicitud', $fechainicio);
        $session->set('estado_solicitud', $estado);
        $respuesta = new Response();
        $respuesta->headers->set('Content-Type', 'text');
        $respuesta->setStatusCode(200);
        $respuesta->setContent("OK");
        return $respuesta;
    }
}
