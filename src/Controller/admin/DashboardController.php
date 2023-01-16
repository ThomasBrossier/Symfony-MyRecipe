<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Admin Dashboard Home
 */
class DashboardController extends AbstractController
{
    /**
     * @return Response
     */
    #[Route('/admin', name: 'app_dashboard')]
    public function index(): Response
    {
        return $this->render('/admin/dashboard.html.twig', [

        ]);
    }
}
