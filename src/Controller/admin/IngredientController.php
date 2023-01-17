<?php

namespace App\Controller\admin;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 *  Admin crud for ingredients
 */
#[Route('admin/ingredient')]
class IngredientController extends AbstractController
{
    /**
     * @param Request $request
     * @param IngredientRepository $ingredientRepository
     * @param PaginatorInterface $paginator
     * @return Response
     */
    #[Route('/', name: 'app_ingredient_index', methods: ['GET'])]
    public function index(Request $request, IngredientRepository $ingredientRepository, PaginatorInterface $paginator): Response
    {
        $filterField = $request->query->get('filterField');
        $filterValue = $request->query->get('filterValue');


        $ingredientsQuery = $ingredientRepository->findBy([],["category" => "ASC"]);
        if($filterField && $filterValue){
            $ingredientsQuery=$ingredientRepository->findByName($filterValue);
        }
        $ingredients = $paginator->paginate(
            $ingredientsQuery, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/,
            ['options' => ['button'=> 'Filtrer']]
        );
        $ingredients->setCustomParameters([
            'align' => 'center',
            'rounded' => true,
        ]);
        return $this->render('admin/ingredient/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * @param Request $request
     * @param IngredientRepository $ingredientRepository
     * @param SluggerInterface $slugger
     * @return Response
     */
    #[Route('/new', name: 'app_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, IngredientRepository $ingredientRepository, SluggerInterface $slugger): Response
    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredient->setSlug($slugger->slug($ingredient->getName()));
            $ingredientRepository->save($ingredient, true);
            return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/ingredient/new.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Ingredient $ingredient
     * @return Response
     */
    #[Route('/{id}', name: 'app_ingredient_show', methods: ['GET'])]
    public function show(Ingredient $ingredient): Response
    {
        return $this->render('admin/ingredient/show.html.twig', [
            'ingredient' => $ingredient,
        ]);
    }

    /**
     * @param Request $request
     * @param Ingredient $ingredient
     * @param IngredientRepository $ingredientRepository
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Ingredient $ingredient, IngredientRepository $ingredientRepository): Response
    {
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientRepository->save($ingredient, true);

            return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/ingredient/edit.html.twig', [
            'ingredient' => $ingredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param Ingredient $ingredient
     * @param IngredientRepository $ingredientRepository
     * @return Response
     */
    #[Route('/{id}', name: 'app_ingredient_delete', methods: ['POST'])]
    public function delete(Request $request, Ingredient $ingredient, IngredientRepository $ingredientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ingredient->getId(), $request->request->get('_token'))) {
            $ingredientRepository->remove($ingredient, true);
        }

        return $this->redirectToRoute('app_ingredient_index', [], Response::HTTP_SEE_OTHER);
    }
}
