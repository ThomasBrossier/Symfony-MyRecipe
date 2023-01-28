<?php

namespace App\Controller\api;

use App\Repository\CategoryRecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/recipe/categories', name: 'app_api_recipe_categories')]
class ApiRecipeCategoriesController extends AbstractController
{
    #[Route('/',  name: 'app_recipe_categories', methods: ['GET'])]
    public function getCategories(CategoryRecipeRepository $categoryRecipeRepository,  SerializerInterface $serializer): Response
    {
        $categories = $categoryRecipeRepository->findBy([],['name'=>'ASC']);

        return $this->json(
            json_decode(
                $serializer->serialize(
                    $categories,
                    'json',
                    [AbstractNormalizer::IGNORED_ATTRIBUTES => ['recipes']]
                ),
                JSON_OBJECT_AS_ARRAY
            ));
    }
}
