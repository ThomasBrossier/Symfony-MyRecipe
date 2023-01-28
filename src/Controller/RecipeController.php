<?php

namespace App\Controller;

use App\Entity\CategoryRecipe;
use App\Entity\Recipe;
use App\Factory\JsonResponseFactory;
use App\Repository\CategoryRecipeRepository;
use App\Repository\RecipeRepository;
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
    public function __construct(private JsonResponseFactory $jsonResponseFactory)
    {
    }
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
     * @param RecipeRepository $recipeRepository
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe, RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->findCompleteOneById($recipe->getId());
        if( $this->getUser()->getUserIdentifier() === $recipe->getAuthor()->getUser()->getUserIdentifier()){
            $isAuthor = true;
        }else{
            $isAuthor = false;
        }
        $json = $this->jsonResponseFactory->create($recipe);
        return $this->render('front/recipe_show.html.twig', [
            'recipe'=> $recipe,
            'json' => $json,
            'isAuthor' => $isAuthor
        ]);
    }

}
