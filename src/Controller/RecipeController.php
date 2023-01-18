<?php

namespace App\Controller;

use App\Entity\CategoryRecipe;
use App\Entity\Recipe;
use App\Repository\CategoryRecipeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 *  Recipe controller to show Recipes & Category Recipe pages
 */
#[Route('/recipe')]
class RecipeController extends AbstractController
{
    /**
     * @param CategoryRecipeRepository $categoryRecipeRepository
     * @return Response
     */
    #[Route('/new', name: 'app_recipe_new', methods: ['GET'])]
    public function index( CategoryRecipeRepository $categoryRecipeRepository): Response
    {
        return $this->render('front/new_recipe.html.twig', [
        ]);
    }

    /**
     * @param Request $request
     * @param CategoryRecipe $categoryRecipe
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/category/{id}', name: 'app_categoryRecipe_show', methods: ['GET'])]
    public function category(Request $request, CategoryRecipe $categoryRecipe, PaginatorInterface $paginator): Response
    {
        $recipesQuery = $categoryRecipe->getRecipes();
        $recipes = $paginator->paginate(
            $recipesQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/,
            ['options' => ['button'=> 'Filtrer']]
        );
        $recipes->setCustomParameters([
            'align' => 'center',
            'rounded' => true,
        ]);
        return $this->render('front/recipe_category.html.twig', [
            'category'=> $categoryRecipe,
            'recipes' => $recipes
        ]);
    }

    /**
     * @param Recipe $recipe
     * @return Response
     */
    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {

        return $this->render('front/recipe_show.html.twig', [
            'recipe'=> $recipe
        ]);
    }
    #[Route('/{id}', name: 'app_recipe_edit', methods: ['GET'])]
    public function editRecipe(Recipe $recipe): Response
    {

        return $this->render('front/recipe_show.html.twig', [
            'recipe'=> $recipe
        ]);
    }
}
