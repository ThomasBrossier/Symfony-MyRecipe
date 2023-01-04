<?php

namespace App\Controller;

use App\Repository\CategoryRecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    #[Route('/recipe/new', name: 'app_recipe_new')]
    public function index( CategoryRecipeRepository $categoryRecipeRepository): Response
    {
        return $this->render('front/new_recipe.html.twig', [
        ]);
    }
}
