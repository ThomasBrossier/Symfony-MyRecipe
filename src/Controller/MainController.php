<?php

namespace App\Controller;

use App\Repository\CategoryRecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(CategoryRecipeRepository $categoryRecipeRepository): Response
    {
        $categories = $categoryRecipeRepository->findAll();
        return $this->render('front/main.html.twig', [
            'categories' => $categories,
        ]);
    }
}
