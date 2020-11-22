<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Paquete;
use App\Form\PaqueteType;
use App\Repository\PaqueteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(Request $request, PaqueteRepository $paqueteRepository)
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

        return $this->render('base.html.twig', [
            'controller_name' => 'HomeController', 
                'paquetes' => $paqueteRepository->findAll(),
            
        ]);
    }
}
