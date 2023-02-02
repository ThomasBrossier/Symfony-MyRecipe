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
            $recipe->setTitle(htmlspecialchars($datas['title'],ENT_NOQUOTES))
                ->setOrigin(htmlspecialchars($datas['origin'],ENT_NOQUOTES))
                ->addCategory($category)
                ->setSlug($slugger->slug($recipe->getTitle()) )
                ->setAuthor($user->getProfile())
                ->setPerson(htmlspecialchars($datas['person'],ENT_NOQUOTES));

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
                $newStep->setContent(htmlspecialchars($step,ENT_NOQUOTES));
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

}

