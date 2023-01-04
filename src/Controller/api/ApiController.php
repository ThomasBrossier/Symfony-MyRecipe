<?php

namespace App\Controller\api;
use App\Entity\CategoryIngredient;
use App\Entity\Ingredient;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Repository\CategoryIngredientRepository;
use App\Repository\CategoryRecipeRepository;
use App\Repository\IngredientRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/recipe/categories', name: 'app_recipe_categories', methods: ['GET'])]
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
    #[Route('/recipe/ingredients', name: 'app_recipe_ingredients', methods: ['GET'])]
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

    #[Route('/recipe/new', name: 'app_recipe_create', methods: ['POST'])]
    public function createRecipe(Request $request,RecipeRepository $recipeRepository, CategoryRecipeRepository $categoryRecipeRepository, RecipeIngredientRepository $recipeIngredientRepository , IngredientRepository $ingredientRepository, SluggerInterface $slugger, UserRepository $userRepository): JsonResponse
    {
        $datas = json_decode($request->getContent());
        $user = $userRepository->findOneBy(['email'=> $this->getUser()->getUserIdentifier() ]) ;
        $category =  $categoryRecipeRepository->find($datas->category);
        $recipe = new Recipe();
        $recipe->setTitle($datas->title)
             ->setOrigin($datas->origin)
             ->addCategory($category)
             ->setSlug($slugger->slug($recipe->getTitle()) )
             ->setAuthor($user->getProfile());
        foreach ($datas->ingredients as $ingredient){
            $newIngredient = $ingredientRepository->find($ingredient->name);
            $recipeIngredient = new RecipeIngredient();
            $recipeIngredient->setQuantity($ingredient->quantity)
            ->setIngredients($newIngredient)
            ->setUnit($ingredient->unit);
            $recipe->addRecipeIngredient($recipeIngredient)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable());
        }
        /*$recipeRepository->save($recipe, true);*/

        return  new JsonResponse(['status'=>'200','result'=>'OK'],201);
    }

}

