<?php

namespace App\Service;

use App\Entity\Recipe;
use App\Entity\RecipeStep;
use App\Repository\RecipeStepRepository;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

class ApiDataTransform
{



    public function __construct( private readonly HtmlSanitizerInterface $htmlSanitizer, private RecipeStepRepository $recipeStepRepository )
    {
    }

    public function formatRecipe($formData) : array{
        $array = [];
        foreach ($formData as $key => $value){
            if($key !== 'picture'){
                if ($key !== 'person'){
                    $array[$key] = json_decode($value);
                }else{
                    $array[$key] =  (int)$value;
                }

            }
        }
        return $array;
    }

    public function repopulateRecipe( $data , Recipe $recipe) : Recipe
    {

        if(isset($data->title)){
            $recipe->setTitle($data->title);
        }
        if (isset($data->person)){
            $recipe->setPerson($data->person);
        }

        if(!empty($data->recipeIngredient) )
        foreach ($data->recipeIngredients as $newRecipeIngredient){
            $id = $data->recipeIngredients->id;
            foreach ($recipe->getRecipeIngredients() as $recipeIngredient){
                if($id === $recipeIngredient->getId()){
                    $recipeIngredient->setUnit($newRecipeIngredient->unit)
                        ->setQuantity($newRecipeIngredient->quantity);
                }
            }
        }
        if(!empty($data->updatedSteps)){
            foreach ($data->updatedSteps as $updatedRecipeStep){
                $id = $updatedRecipeStep->id;
                foreach ($recipe->getRecipeSteps() as $recipeStep ){
                    if($id === $recipeStep->getId()){
                        $recipeStep->setContent( $updatedRecipeStep->content);
                    }
                }
            }
        }
        if(!empty($data->addedSteps)){
            foreach ($data->addedSteps as $newStep){
                $step = new RecipeStep();
                $step->setContent($newStep->content);
                $recipe->addRecipeStep($step);
            }
        }
        if(!empty($data->removedSteps)){
            foreach ($data->removedSteps as $removedStep){
                $recipeToRemove = $this->recipeStepRepository->find($removedStep->id);
                $recipe->removeRecipeStep($recipeToRemove);
            }
        }

        return $recipe;
    }

}