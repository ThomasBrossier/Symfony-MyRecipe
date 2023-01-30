<?php

namespace App\Controller\api;

use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api',name: 'app_api_recipe_')]
class ApiIngredientsController extends AbstractController
{
    #[Route('/recipe/ingredients', name: 'ingredients', methods: ['GET'])]
    public function getIngredients(Request $request, IngredientRepository $ingredientRepository , SerializerInterface $serializer): Response
    {
        $ingredients = $ingredientRepository->findLike($request->get('ingredient'));
        return $this->json(
            json_decode(
                $serializer->serialize(
                    $ingredients,
                    'json',
                    [AbstractNormalizer::IGNORED_ATTRIBUTES => ['recipeIngredients','category','imageFile']]
                ),
                JSON_OBJECT_AS_ARRAY
            )
        );
    }
}
