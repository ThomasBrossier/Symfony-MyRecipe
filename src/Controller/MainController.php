<?php

namespace App\Controller;

use App\Repository\CategoryRecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Main controller to show Home page
 */
class MainController extends AbstractController
{
    /**
     * @param CategoryRecipeRepository $categoryRecipeRepository
     * @return Response
     */
    #[Route('/', name: 'app_main')]
    public function index(CategoryRecipeRepository $categoryRecipeRepository): Response
    {
        $categories = $categoryRecipeRepository->findAll();
        return $this->render('front/main.html.twig', [
            'categories' => $categories,
        ]);
    }
}
