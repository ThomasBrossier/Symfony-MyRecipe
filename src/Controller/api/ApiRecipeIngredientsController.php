<?php

namespace App\Controller\api;

use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Repository\IngredientRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/recipe/{id}', name: 'app_api_recipe_')]
class ApiRecipeIngredientsController extends AbstractController
{
    #[Route('/deleteRecipeIngredient', name: 'recipeIngredient_delete', methods: ['POST'])]
    public function deleteRecipeIngredient(Request $request,
                                           Recipe $recipe ,
                                           RecipeRepository $recipeRepository,
                                           UserRepository $userRepository,
                                           RecipeIngredientRepository $recipeIngredientRepository,
    ): Response
    {
        $user = $userRepository->findOneBy(['email'=> $this->getUser()->getUserIdentifier() ]) ;
        if($user->getId() !== $recipe->getAuthor()->getUser()->getId()){
            return new JsonResponse(['status'=>'403','result'=>'Vous ne pouvez pas supprimer cette étape'],403);
        }

        $idRecipeIngredientToDelete =  json_decode($request->getContent());
        $recipeIngredientToDelete = $recipeIngredientRepository->find($idRecipeIngredientToDelete);
        if(!$recipeIngredientToDelete){
            return new JsonResponse(['status'=>'500','result'=>'Erreur lors de la suppression'],500);
        }else{
            $recipe->removeRecipeIngredient($recipeIngredientToDelete);
            $recipeRepository->save($recipe,true);
        }

        return new JsonResponse(['status'=>'200','result'=>'Ingredient supprimé'],200);
    }


    #[Route('/recipeIngredient/new', name: 'recipeIngredient_new', methods: ['POST'])]
    public function newRecipeIngredient(Request $request,
                                           Recipe $recipe ,
                                           RecipeRepository $recipeRepository,
                                           UserRepository $userRepository,
                                           IngredientRepository $ingredientRepository,
                                           RecipeIngredientRepository $recipeIngredientRepository,
    ): Response
    {
        $user = $userRepository->findOneBy(['email'=> $this->getUser()->getUserIdentifier() ]) ;
        if($user->getId() !== $recipe->getAuthor()->getUser()->getId()){
            return new JsonResponse(['status'=>'403','result'=>'Vous ne pouvez pas supprimer cette étape'],403);
        }
        $data = json_decode($request->getContent()) ;
        $ingredient = $ingredientRepository->find($data->ingredient->id);
        if(!$ingredient){
            return new JsonResponse(['status'=>'403','result'=>'Erreur de création'],500);
        }else{
            $recipeIngredient = new RecipeIngredient();
            $recipeIngredient->setIngredient($ingredient)
                ->setQuantity($data->quantity)
                ->setUnit($data->unit)
                ->setRecipes($recipe);
            $recipeIngredientRepository->save($recipeIngredient,true);
        }

        return new JsonResponse(['status'=>'200','result'=>'Ingredient Ajouté'],200);
    }



    #[Route('/recipeIngredient', name: 'recipeIngredient_update', methods: ['POST'])]
    public function updateRecipeIngredient(Request $request,
                                           Recipe $recipe ,
                                           RecipeRepository $recipeRepository,
                                           UserRepository $userRepository,
                                           IngredientRepository $ingredientRepository,
                                           RecipeIngredientRepository $recipeIngredientRepository,
    ): Response
    {
        $user = $userRepository->findOneBy(['email'=> $this->getUser()->getUserIdentifier() ]) ;
        if($user->getId() !== $recipe->getAuthor()->getUser()->getId()){
            return new JsonResponse(['status'=>'403','result'=>'Vous ne pouvez pas supprimer cette étape'],403);
        }
        $data = json_decode($request->getContent()) ;
        $ingredient = $ingredientRepository->find($data->ingredient->id);
        $recipeIngredient = $recipeIngredientRepository->find($data->id);
        if(!$ingredient || !$recipeIngredient){
            return new JsonResponse(['status'=>'403','result'=>'Erreur de modification'],500);
        }else{
            $recipeIngredient->setIngredient($ingredient)
                ->setQuantity($data->quantity)
                ->setUnit($data->unit)
                ->setRecipes($recipe);
            $recipeIngredientRepository->save($recipeIngredient,true);
        }

        return new JsonResponse(['status'=>'200','result'=>'Ingredient modifié'],200);
    }
}
