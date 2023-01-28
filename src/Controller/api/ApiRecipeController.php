<?php

namespace App\Controller\api;
use App\Entity\Recipe;
use App\Entity\RecipeIngredient;
use App\Entity\RecipeStep;
use App\Repository\CategoryRecipeRepository;
use App\Repository\IngredientRepository;
use App\Repository\RecipeIngredientRepository;
use App\Repository\RecipeRepository;
use App\Repository\RecipeStepRepository;
use App\Repository\UserRepository;
use App\Service\ApiDataTransform;
use Couchbase\User;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 *  Controller for api crud requests
 */
#[Route('/api')]

class ApiRecipeController extends AbstractController
{

    /**
     * @param Request $request
     * @param IngredientRepository $ingredientRepository
     * @param SerializerInterface $serializer
     * @return Response
     */
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

    /**
     * @param Request $request
     * @param RecipeRepository $recipeRepository
     * @param CategoryRecipeRepository $categoryRecipeRepository
     * @param RecipeIngredientRepository $recipeIngredientRepository
     * @param IngredientRepository $ingredientRepository
     * @param SluggerInterface $slugger
     * @param UserRepository $userRepository
     * @param ValidatorInterface $validator
     * @param ApiDataTransform $dataTransform
     * @return JsonResponse
     */
    #[Route('/recipe/new', name: 'app_recipe_create', methods: ['POST'])]
    public function createRecipe(Request $request,
                                 RecipeRepository $recipeRepository,
                                 CategoryRecipeRepository $categoryRecipeRepository,
                                 RecipeIngredientRepository $recipeIngredientRepository ,
                                 IngredientRepository $ingredientRepository,
                                 SluggerInterface $slugger,
                                 UserRepository $userRepository,
                                 ValidatorInterface $validator,
                                ApiDataTransform $dataTransform ): JsonResponse

    {
         $file = $request->files->get('picture') ;
         $datas = $dataTransform->formatRecipe($request->request->all());
        if (!empty($datas) && !empty($file)) {
            $user = $userRepository->findOneBy(['email'=> $this->getUser()->getUserIdentifier() ]) ;
            $category =  $categoryRecipeRepository->find($datas['category']);
            $recipe = new Recipe();
            $recipe->setTitle($datas['title'])
                ->setOrigin($datas['origin'])
                ->addCategory($category)
                ->setSlug($slugger->slug($recipe->getTitle()) )
                ->setAuthor($user->getProfile())
                ->setPerson($datas['person']);

            foreach ($datas['ingredients'] as $ingredient){
                $newIngredient = $ingredientRepository->find($ingredient->name);
                $recipeIngredient = new RecipeIngredient();

                $recipeIngredient->setQuantity($ingredient->quantity)
                    ->setIngredient($newIngredient)
                    ->setUnit($ingredient->unit);
                $recipe->addRecipeIngredient($recipeIngredient);
            }

            foreach ($datas['steps'] as $step){
                $newStep = new RecipeStep();
                $newStep->setContent($step);
                $recipe->addRecipeStep($newStep);
            }
            $recipe->setImageFile($file);
            $recipe->setCreatedAt(new \DateTimeImmutable());
            $errors = $validator->validate($recipe);
            if(count($errors) > 0){
                return  new JsonResponse(['status'=>'400','error'=> "Une erreur est survenue",400]);
            }
            $recipeRepository->save($recipe, true);
            return  new JsonResponse(['status'=>'200','result'=>'Recette enregistrée'],201);
        }else{
            return  new JsonResponse(['status'=>'400','error'=>'Le formulaire n\'a pas été rempli correctement'],400);
        }
    }

    #[Route('/recipe/update/{id}', name: 'app_recipe_update', methods: ['POST'])]
    public function updateRecipe(Request $request, UserRepository $userRepository, Recipe $recipe): Response
    {
        $user = $userRepository->findOneBy(['email'=> $this->getUser()->getUserIdentifier() ]) ;
        if($user->getId() !== $recipe->getAuthor()->getUser()->getId()){
            return new JsonResponse(['status'=>'403','result'=>'Vous ne pouvez pas modifier cette recette'],403);
        }
        return new JsonResponse(['status'=>'200','result'=>'Recette modifiée'],200);
    }




}

