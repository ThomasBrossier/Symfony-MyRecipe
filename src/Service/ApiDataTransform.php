<?php

namespace App\Service;

use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\RecipeStep;
use App\Repository\IngredientRepository;
use App\Repository\RecipeStepRepository;
use Symfony\Component\HtmlSanitizer\HtmlSanitizer;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerConfig;
use Symfony\Component\HtmlSanitizer\HtmlSanitizerInterface;

class ApiDataTransform
{

    public function formatRecipe($formData) : array{
        $array = [];
        foreach ($formData as $key => $value){
            if($key !== 'picture'){
                if ($key !== 'person'){
                    $array[$key] = json_decode(htmlspecialchars($value,ENT_NOQUOTES));
                }else{
                    $array[$key] =  (int)$value;
                }
            }
        }
        return $array;
    }

}