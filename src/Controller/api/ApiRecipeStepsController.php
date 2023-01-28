<?php

namespace App\Controller\api;

use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Repository\RecipeRepository;
use App\Repository\RecipeStepRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/recipe/{id}', name: 'app_api_recipe_')]
class ApiRecipeStepsController extends AbstractController
{
    #[Route('/step/delete', name: 'step_delete', methods: ['POST'])]
    public function deleteRecipeStep(Request $request,
                                     Recipe $recipe ,
                                     RecipeRepository $recipeRepository,
                                     UserRepository $userRepository,
                                     RecipeStepRepository $recipeStepRepository,
    ): Response
    {
        $user = $userRepository->findOneBy(['email'=> $this->getUser()->getUserIdentifier() ]) ;
        if($user->getId() !== $recipe->getAuthor()->getUser()->getId()){
            return new JsonResponse(['status'=>'403','result'=>'Vous ne pouvez pas supprimer cette étape'],403);
        }

        $idStepToDelete =  json_decode($request->getContent())?->id;
        $stepToDelete = $recipeStepRepository->find($idStepToDelete);
        if(!$stepToDelete){
            return new JsonResponse(['status'=>'500','result'=>'Erreur lors de la suppression'],500);
        }else{
            $recipe->removeRecipeStep($stepToDelete);
            $recipeRepository->save($recipe, true);
        }

        return new JsonResponse(['status'=>'200','result'=>'Etape supprimé'],200);
    }

    #[Route('/step', name: 'step_update', methods: ['POST'])]
    public function updateRecipeStep(Request $request,
                                     Recipe $recipe ,
                                     RecipeRepository $recipeRepository,
                                     UserRepository $userRepository,
                                     RecipeStepRepository $recipeStepRepository,
    ): Response
    {
        $user = $userRepository->findOneBy(['email'=> $this->getUser()->getUserIdentifier() ]) ;
        if($user->getId() !== $recipe->getAuthor()->getUser()->getId()){
            return new JsonResponse(['status'=>'403','result'=>'Vous ne pouvez pas modifier cette étape'],403);
        }
        $step =  json_decode($request->getContent());
        $stepToUpdate = $recipeStepRepository->find($step->id);
        if(!$stepToUpdate){
            return new JsonResponse(['status'=>'500','result'=>'Erreur lors de la mise à jour'],500);
        }else{
            $stepToUpdate->setContent($step->content);
            $recipeStepRepository->save($stepToUpdate,true);
        }

        return new JsonResponse(['status'=>'200','result'=>'Etapes mise à jour'],200);
    }

    #[Route('/step/new', name: 'step_new', methods: ['POST'])]
    public function newRecipeStep(Request $request,
                                  Recipe $recipe ,
                                  RecipeRepository $recipeRepository,
                                  UserRepository $userRepository,
                                  RecipeStepRepository $recipeStepRepository,
    ): Response
    {
        $user = $userRepository->findOneBy(['email'=> $this->getUser()->getUserIdentifier() ]) ;
        if($user->getId() !== $recipe->getAuthor()->getUser()->getId()){
            return new JsonResponse(['status'=>'403','result'=>'Vous ne pouvez pas modifier les étapes'],403);
        }
        $step =  json_decode($request->getContent());
        $newStep = new RecipeStep();
        $newStep->setContent($step->content)
            ->setRecipe($recipe);
        $recipeStepRepository->save($newStep,true);

        return new JsonResponse(['status'=>'200','result'=>'Etapes ajouté'],200);
    }
}
