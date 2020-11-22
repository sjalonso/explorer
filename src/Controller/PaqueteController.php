<?php

namespace App\Controller;

use App\Entity\Paquete;
use App\Entity\Reserva;
use App\Entity\Ruta;
use App\Form\PaqueteType;
use App\Repository\PaqueteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Dompdf\Dompdf;
use Dompdf\Options;
use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_IComparable;
use PHPExcel_Worksheet_HeaderFooter;
use PHPExcel_Worksheet_Drawing;

/**
 * @Route("/paquete")
 */
class PaqueteController extends AbstractController
{
    /**
     * @Route("/", name="paquete_index", methods={"GET"})
     */
    public function index(Request $request, PaqueteRepository $paqueteRepository): Response
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

        $entityManager = $this->getDoctrine()->getManager();
        $rutas = $entityManager->getRepository(Ruta::class)->findAll();
        return $this->render('paquete/index.html.twig', [
            'paquetes' => $paqueteRepository->findAll(),
            'rutas' => $rutas
        ]);
    }

     /**
     * @Route("/2", name="paquete_index2", methods={"GET"})
     */
    public function index2(Request $request, PaqueteRepository $paqueteRepository): Response
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

        $entityManager = $this->getDoctrine()->getManager();
        $rutas = $entityManager->getRepository(Ruta::class)->findAll();
        return $this->render('paquete/index2.html.twig', [
            'paquetes' => $paqueteRepository->findAll(),
            'rutas' => $rutas
        ]);
    }
    

    /**
     * @Route("/reporte", name="paquete_reporte", methods={"GET"})
     */
    public function reporte(Request $request, PaqueteRepository $paqueteRepository): Response
    {
        $session = $request->getSession();
        $session->set('fechainicio_solicitud', "");
        $session->set('estado_solicitud', "");

        return $this->render('paquete/reporte.html.twig', [
            'paquetes' => $paqueteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="paquete_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $paquete = new Paquete();
        $form = $this->createForm(PaqueteType::class, $paquete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            //guardar cant asientos original
            $paquete->setCantasientooriginal($paquete->getCantasiento());
            $entityManager->persist($paquete);
            $entityManager->flush();
            if($paquete->getEstadoPaquete()->getTipoestado() == "Activo")
                return $this->redirectToRoute('paquete_index');
            else return $this->redirectToRoute('paquete_index2');
        }

        return $this->render('paquete/new.html.twig', [
            'paquete' => $paquete,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="paquete_show", methods={"GET"})
     */
    public function show(Paquete $paquete): Response
    {
        $reservas = array();
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $sucursal = $user->getSucursal();
        $idpaquete = $paquete->getId();
        if($sucursal) {
            $id = $sucursal->getId();
            $reservas = $entityManager->getRepository(Reserva::class)->findBySucursalAndPaquete($id, $idpaquete);
            //Si el usuario es COMERCIAL buscar solo las reservas hechas por él
            if($this->isGranted('ROLE_USER')){
                $iduser = $user->getId();
                $reservas = $entityManager->getRepository(Reserva::class)->findBySucursalPaqueteAndUser($id, $idpaquete, $iduser);
            }
        }
        else
            $reservas = $entityManager->getRepository(Reserva::class)->findByPaquete($idpaquete);
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_RECEPTOR'))
            $reservas = $entityManager->getRepository(Reserva::class)->findByPaquete($idpaquete);

        return $this->render('paquete/show.html.twig', [
            'paquete' => $paquete,
            'reservas' => $reservas
        ]);
    }

     /**
     * @Route("/printpaquete/{id}", name="imprimeshow_show", methods={"GET"})
     */
    public function imprimeshow(Paquete $paquete): Response
    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $reservas = array();
        $entityManager = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $sucursal = $user->getSucursal();
        $idpaquete = $paquete->getId();
        if($sucursal) {
            $id = $sucursal->getId();
            $reservas = $entityManager->getRepository(Reserva::class)->findBySucursalAndPaquete($id, $idpaquete);
            //Si el usuario es COMERCIAL buscar solo las reservas hechas por él
            if($this->isGranted('ROLE_USER')){
                $iduser = $user->getId();
                $reservas = $entityManager->getRepository(Reserva::class)->findBySucursalPaqueteAndUser($id, $idpaquete, $iduser);
            }
        }
        else
            $reservas = $entityManager->getRepository(Reserva::class)->findByPaquete($idpaquete);
        if ($this->isGranted('ROLE_ADMIN') || $this->isGranted('ROLE_RECEPTOR'))
            $reservas = $entityManager->getRepository(Reserva::class)->findByPaquete($idpaquete);

        // Retrieve the HTML generated in our twig file
         $html = $this->renderView('paquete/printpaquete.html.twig', [
            'paquete' => $paquete,
             'reservas' => $reservas
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


      
    }


 
    /**
     * @Route("/{id}/edit", name="paquete_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Paquete $paquete): Response
    {
        $form = $this->createForm(PaqueteType::class, $paquete);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            if($paquete->getEstadoPaquete()->getTipoestado() == "Activo")
                return $this->redirectToRoute('paquete_index');
            else return $this->redirectToRoute('paquete_index2');
        }

        return $this->render('paquete/edit.html.twig', [
            'paquete' => $paquete,
            'error' => '',
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/duplicar", name="paquete_duplicar", methods={"DELETE"})
     */
    public function duplicar(Request $request, Paquete $paquete): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        //Si hay duplicados de este paquete, incrementar con un sufijo el valor del codigo
        $valor = "dup ".$paquete->getCodigopaquete();
        $nuevoCodigo = $valor;
        $cantDuplicados = $entityManager->getRepository(Paquete::class)->findCountLikeCodigopaquete($valor);
        if($cantDuplicados && $cantDuplicados > 0) {
            $cantDuplicados += 1;
            $nuevoCodigo = "dup " . $paquete->getCodigopaquete() . "-" . $cantDuplicados;
        }

        $duplicado = new Paquete();
        $duplicado->setFechaida($paquete->getFechaida());
        $duplicado->setFecharegreso($paquete->getFecharegreso());
        $duplicado->setCantasiento($paquete->getCantasientooriginal());
        $duplicado->setCantasientooriginal($paquete->getCantasientooriginal());
        $duplicado->setPreciocuba($paquete->getPreciocuba());
        $duplicado->setPreciodestino($paquete->getPreciodestino());
        $duplicado->setPrecioinfante($paquete->getPrecioinfante());
        $duplicado->setPreciodestinodoble($paquete->getPreciodestinodoble());
        $duplicado->setPreciodestinotriple($paquete->getPreciodestinotriple());
        $duplicado->setPreciodestinocuadruple($paquete->getPreciodestinocuadruple());
        $duplicado->setPreciominisimple($paquete->getPreciominisimple());
        $duplicado->setPreciominidoble($paquete->getPreciominidoble());
        $duplicado->setPreciominitriple($paquete->getPreciominitriple());
        $duplicado->setPreciominicuadruple($paquete->getPreciominicuadruple());
        $duplicado->setPreciominininno($paquete->getPreciominininno());
        $duplicado->setEstadoPaquete($paquete->getEstadoPaquete());
        $duplicado->setRuta($paquete->getRuta());
        $duplicado->setCodigopaquete($nuevoCodigo);
        $duplicado->setPreciomininvsimple($paquete->getPreciomininvsimple());
        $duplicado->setPreciomininvdoble($paquete->getPreciomininvdoble());
        $duplicado->setPreciomininvtriple($paquete->getPreciomininvtriple());
        $duplicado->setPreciomininvcuadruple($paquete->getPreciomininvcuadruple());
        $duplicado->setPreciomininvinfante($paquete->getPreciomininvinfante());
        $duplicado->setPrecioinfantedestino($paquete->getPrecioinfantedestino());
        $duplicado->setAerolineas($paquete->getAerolineas());
        $duplicado->setDescripcion($paquete->getDescripcion() ? $paquete->getDescripcion() : "");
        $duplicado->setFechalimite($paquete->getFechalimite());
        $entityManager->persist($duplicado);
        $entityManager->flush();

        if($paquete->getEstadoPaquete()->getTipoestado() == "Activo")
            return $this->redirectToRoute('paquete_index');
        else return $this->redirectToRoute('paquete_index2');
    }

    /**
     * @Route("/{id}", name="paquete_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Paquete $paquete): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paquete->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($paquete);
            $entityManager->flush();
        }

        return $this->redirectToRoute('paquete_index');
    }

    /**
     * @Route("/{id}/exportar_listado_visado", name="exportar_listado_visado", methods={"GET","POST"})
     */
    public function exportarListadoVisa(Request $request, Paquete $paquete): Response
    {
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Explore Caribbean")
            ->setLastModifiedBy("Explore Caribbean")
            ->setTitle("Listado Visado para Salida del ".$paquete->getFechaida()->format("d-m-Y")." al ".$paquete->getFecharegreso()->format("d-m-Y"))
            ->setSubject("Listado Visado");
        // Crear el Header Footer
        $header = new PHPExcel_Worksheet_HeaderFooter();
        $header->setFirstHeader("Listado Visado");

        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle('del '.$paquete->getFechaida()->format("d-m-Y").' al '.$paquete->getFecharegreso()->format("d-m-Y"));
        $style = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'font' => array('name' => 'Arial', 'bold' => true, 'size' => 16),
        );
        $style2 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'font' => array('bold' => true, 'size' => 14),
            'borders' => array('allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_MEDIUM, 'color' => array('rgb' => '000000')))
        );
        $style3 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_BOTTOM),
            'borders' => array('allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000')))
        );
        $style4 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array('allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000')))
        );
        $style4_1 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array(
                'top' => array('style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000')),
                'left' => array('style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000')),
                'bottom' => array('style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000')),
                'right' => array('style' => \PHPExcel_Style_Border::BORDER_MEDIUM, 'color' => array('rgb' => '000000'))
            )
        );
        $style4_2 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array(
                'top' => array('style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000')),
                'left' => array('style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000')),
                'bottom' => array('style' => \PHPExcel_Style_Border::BORDER_MEDIUM, 'color' => array('rgb' => '000000')),
                'right' => array('style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000'))
            )
        );
        $style4_3 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'borders' => array(
                'top' => array('style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000')),
                'left' => array('style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000')),
                'bottom' => array('style' => \PHPExcel_Style_Border::BORDER_MEDIUM, 'color' => array('rgb' => '000000')),
                'right' => array('style' => \PHPExcel_Style_Border::BORDER_MEDIUM, 'color' => array('rgb' => '000000'))
            )
        );
        $style4_4 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_BOTTOM),
            'borders' => array(
                'top' => array('style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000')),
                'left' => array('style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000')),
                'bottom' => array('style' => \PHPExcel_Style_Border::BORDER_MEDIUM, 'color' => array('rgb' => '000000')),
                'right' => array('style' => \PHPExcel_Style_Border::BORDER_DOTTED, 'color' => array('rgb' => '000000'))
            )
        );
        $style5 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_BOTTOM),
            'font' => array('size' => 16),
        );
        $style6 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_BOTTOM),
            'font' => array('bold' => true, 'size' => 16),
        );

        $objPHPExcel->getActiveSheet()->mergeCells("A1:K3");
        $objPHPExcel->getActiveSheet()->getStyle("A1:K3")->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle("A4:K4")->applyFromArray($style2);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth("11.30");
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth("7.15");
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth("26.15");
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth("34.86");
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth("31.15");
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth("20.70");
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("G")->setWidth("28");
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("H")->setWidth("17.70");
        $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("I")->setWidth("17.85");
        $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("J")->setWidth("13.70");
        $objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("K")->setWidth("13.70");

        $objPHPExcel->getActiveSheet()->getRowDimension(1)->setRowHeight(14.5);
        $objPHPExcel->getActiveSheet()->getRowDimension(2)->setRowHeight(14.5);
        $objPHPExcel->getActiveSheet()->getRowDimension(3)->setRowHeight(14.5);
        $objPHPExcel->getActiveSheet()->getRowDimension(4)->setRowHeight(41.5);

        $idpaquete = $paquete->getId();
        $reservas = $em->getRepository(Reserva::class)->findByPaquete($idpaquete);
        $cantReservas = $reservas != null ? count($reservas) : 0;
        $objPHPExcel->getActiveSheet()->getStyle('A4:K4')->getAlignment()->setWrapText(true);
        $objPHPExcel->getActiveSheet()->setCellValue("A1", "LISTADO PARA VISADO ".$paquete->getCodigopaquete()."-RESERVACION HECHA POR EXPLORE CARIBBEAN- del ".$paquete->getFechaida()->format("d/m/Y")." al ".$paquete->getFecharegreso()->format("d/m/Y")." | Total ".$cantReservas." paxs");
        $objPHPExcel->getActiveSheet()->setCellValue("B4", "TITLE");
        $objPHPExcel->getActiveSheet()->setCellValue("C4", "NOMBRE");
        $objPHPExcel->getActiveSheet()->setCellValue("D4", "APELLIDO");
        $objPHPExcel->getActiveSheet()->setCellValue("E4", "FECHA DE NACIMIENTO");
        $objPHPExcel->getActiveSheet()->setCellValue("F4", "# de PASAPORTE");
        $objPHPExcel->getActiveSheet()->setCellValue("G4", "Fecha de EXPIRACION");
        $objPHPExcel->getActiveSheet()->setCellValue("H4", "DISTRIBUCION \nHABITACION");
        $objPHPExcel->getActiveSheet()->setCellValue("I4", "AEROLINEA");
        $objPHPExcel->getActiveSheet()->setCellValue("J4", "FECHA DE \nPARTIDA");
        $objPHPExcel->getActiveSheet()->setCellValue("K4", "FECHA DE \nRETORNO");
        $contador = 5;
        for($i=0;$i<$cantReservas;$i++){
            $reserva = $reservas[$i];
            $objPHPExcel->getActiveSheet()->getRowDimension($contador)->setRowHeight(27.5);
            $objPHPExcel->getActiveSheet()->getStyle("A".$contador)->applyFromArray($style3);
            $objPHPExcel->getActiveSheet()->getStyle("B".$contador.":J".$contador)->applyFromArray($style4);
            $objPHPExcel->getActiveSheet()->getStyle("K".$contador)->applyFromArray($style4_1);
            if($i == $cantReservas-1) {
                $objPHPExcel->getActiveSheet()->getStyle("A" . $contador . ":J" . $contador)->applyFromArray($style4_2);
                $objPHPExcel->getActiveSheet()->getStyle("K".$contador)->applyFromArray($style4_3);
                $objPHPExcel->getActiveSheet()->getStyle("A".$contador)->applyFromArray($style4_4);
            }
            $objPHPExcel->getActiveSheet()->setCellValue("A".$contador, $i+1);
            $objPHPExcel->getActiveSheet()->setCellValue("B".$contador, $reserva->getCliente()->getTitulo());
            $objPHPExcel->getActiveSheet()->setCellValue("C".$contador, $reserva->getCliente()->getNombre());
            $objPHPExcel->getActiveSheet()->setCellValue("D".$contador, $reserva->getCliente()->getApellido());
            $objPHPExcel->getActiveSheet()->setCellValue("E".$contador, $reserva->getCliente()->getFechanacimiento()->format("d/m/Y"));
            $objPHPExcel->getActiveSheet()->setCellValue("F".$contador, $reserva->getCliente()->getPasaporte());
            $objPHPExcel->getActiveSheet()->setCellValue("G".$contador, $reserva->getCliente()->getFechaexpiracion()->format("d/m/Y"));
            $objPHPExcel->getActiveSheet()->setCellValue("H".$contador, $reserva->getHospedaje());
            $objPHPExcel->getActiveSheet()->setCellValue("I".$contador, $paquete->getAerolineas());
            $objPHPExcel->getActiveSheet()->setCellValue("J".$contador, $paquete->getFechaida()->format("d/m/Y"));
            $objPHPExcel->getActiveSheet()->setCellValue("K".$contador, $paquete->getFecharegreso()->format("d/m/Y"));
            $contador++;
        }
        $contador += 2;
        $dia = date("d");
        if(substr($dia, 0, 1) == "0")
            $dia = substr($dia, 1);
        $mes = date("m");
        $anno = date("Y");
        $mes = $this->obtenerNombreMes($mes);
        $objPHPExcel->getActiveSheet()->getStyle("F".$contador)->applyFromArray($style5);
        $objPHPExcel->getActiveSheet()->getStyle("I".$contador)->applyFromArray($style6);
        $objPHPExcel->getActiveSheet()->setCellValue("F".$contador, "Fecha: ".$dia." DE ".$mes." ".$anno);
        $objPHPExcel->getActiveSheet()->setCellValue("I".$contador, "Grand Total PAX");
        $contador++;
        $objPHPExcel->getActiveSheet()->getStyle("I".$contador)->applyFromArray($style6);
        $objPHPExcel->getActiveSheet()->setCellValue("I".$contador, $cantReservas);
        $contador += 3;
        $objPHPExcel->getActiveSheet()->getStyle("D".$contador)->applyFromArray($style6);
        $objPHPExcel->getActiveSheet()->setCellValue("D".$contador, "Firma_______________________");

        // Redirect output to a client’s web browser (Excel2007)
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="Listado '.$paquete->getCodigopaquete().'.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->prepare($request);
        $response->sendHeaders();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit();
    }

    /**
     * @Route("/{id}/exportar_rooming_list", name="exportar_rooming_list", methods={"GET","POST"})
     */
    public function exportarRoomingList(Request $request, Paquete $paquete): Response
    {
        $em = $this->getDoctrine()->getManager();
        $response = new Response();
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Explore Caribbean")
            ->setLastModifiedBy("Explore Caribbean")
            ->setTitle("ROOMLIST de ".$paquete->getFechaida()->format("d/m/Y")." al ".$paquete->getFecharegreso()->format("d/m/Y"))
            ->setSubject("REP DOM-Listado Visado");
        // Crear el Header Footer
        $header = new PHPExcel_Worksheet_HeaderFooter();
        $header->setFirstHeader("ROOMLIST");
        $objPHPExcel->setActiveSheetIndex(0);
        $objPHPExcel->getActiveSheet()->setTitle("PAQUETE NORMAL");
        //Estilos
        $style = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER),
            'font' => array('bold' => true, 'size' => 14),
        );
        $style2 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_BOTTOM),
            'font' => array('bold' => true, 'size' => 11),
            'borders' => array('allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_MEDIUM, 'color' => array('rgb' => '000000')))
        );
        $style3 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_LEFT,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_BOTTOM),
            'font' => array('size' => 11),
            'borders' => array('allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')))
        );
        $style4 = array(
            'alignment' => array('horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                'vertical' => \PHPExcel_Style_Alignment::VERTICAL_BOTTOM),
            'font' => array('size' => 11),
            'borders' => array('allborders' => array(
                'style' => \PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '000000')))
        );

        $objPHPExcel->getActiveSheet()->mergeCells("A1:F2");
        $objPHPExcel->getActiveSheet()->getStyle("A1:F2")->applyFromArray($style);
        $objPHPExcel->getActiveSheet()->getStyle("A3:F4")->applyFromArray($style2);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("A")->setWidth("3.90");
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("B")->setWidth("17.45");
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("C")->setWidth("24.60");
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("D")->setWidth("21");
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("E")->setWidth("25");
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(false);
        $objPHPExcel->getActiveSheet()->getColumnDimension("F")->setWidth("24");

        $objPHPExcel->getActiveSheet()->setCellValue("A1", "ROOMING LIST de ".$paquete->getFechaida()->format("d/m/Y")." al ".$paquete->getFecharegreso()->format("d/m/Y"));
        $objPHPExcel->getActiveSheet()->setCellValue("B3", "APELLIDO");
        $objPHPExcel->getActiveSheet()->setCellValue("C3", "NOMBRE");
        $objPHPExcel->getActiveSheet()->setCellValue("D3", "DISTRIBUCION");
        $objPHPExcel->getActiveSheet()->setCellValue("E3", "BIRTHDAY");
        $objPHPExcel->getActiveSheet()->setCellValue("F3", "Ruta");
        $contador = 5;
        $idpaquete = $paquete->getId();
        $reservas = $em->getRepository(Reserva::class)->findByPaquete($idpaquete);
        $cantReservas = $reservas != null ? count($reservas) : 0;
        for($i=0;$i<$cantReservas;$i++){
            $objPHPExcel->getActiveSheet()->getStyle("B".$contador.":F".$contador)->applyFromArray($style3);
            $objPHPExcel->getActiveSheet()->getStyle("A".$contador)->applyFromArray($style4);
            $reserva = $reservas[$i];
            $objPHPExcel->getActiveSheet()->setCellValue("A".$contador, $i+1);
            $objPHPExcel->getActiveSheet()->setCellValue("B".$contador, $reserva->getCliente()->getApellido());
            $objPHPExcel->getActiveSheet()->setCellValue("C".$contador, $reserva->getCliente()->getNombre());
            $objPHPExcel->getActiveSheet()->setCellValue("D".$contador, $reserva->getHospedaje());
            $objPHPExcel->getActiveSheet()->setCellValue("E".$contador, $reserva->getCliente()->getFechanacimiento()->format("d/m/Y"));
            $objPHPExcel->getActiveSheet()->setCellValue("F".$contador, $paquete->getRuta());

            $contador++;
        }

        //Insertar imagen
        $objDrawing = new PHPExcel_Worksheet_Drawing();
        $objDrawing->setName("logo Explore Caribbean");
        $objDrawing->setDescription("Logo de Explore Caribbean");
        $objDrawing->setPath("./images/logo2.png");
        $objDrawing->setOffsetX(5);
        $objDrawing->setOffsetY(5);
        $objDrawing->setCoordinates("A1");
        $objDrawing->setHeight(70);
        $objDrawing->setWidth(65);
        $objDrawing->setWorksheet($objPHPExcel->setActiveSheetIndex(0));
        // Redirect output to a client’s web browser (Excel2007)
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="ROOMLIST '.$paquete->getCodigopaquete().'.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');
        $response->prepare($request);
        $response->sendHeaders();
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit();
    }

    function obtenerNombreMes($mes){
        if($mes == "01")
            return "ENERO";
        if($mes == "02")
            return "FEBRERO";
        if($mes == "03")
            return "MARZO";
        if($mes == "04")
            return "ABRIL";
        if($mes == "05")
            return "MAYO";
        if($mes == "06")
            return "JUNIO";
        if($mes == "07")
            return "JULIO";
        if($mes == "08")
            return "AGOSTO";
        if($mes == "09")
            return "SEPTIEMBRE";
        if($mes == "10")
            return "OCTUBRE";
        if($mes == "11")
            return "NOVIEMBRE";
        if($mes == "12")
            return "DICIEMBRE";
    }
}
