<?php

namespace App\Service;

class ApiDataTransform
{
function formatRecipe($formData) : array{
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
}