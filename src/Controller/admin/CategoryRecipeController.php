<?php

namespace App\Controller\admin;

use App\Entity\CategoryRecipe;
use App\Form\CategoryRecipeType;
use App\Repository\CategoryRecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Admin Crud for Recipe categories
 */
#[Route('admin/category/recipe')]
class CategoryRecipeController extends AbstractController
{
    /**
     * @param CategoryRecipeRepository $categoryRecipeRepository
     * @return Response
     */
    #[Route('/', name: 'app_category_recipe_index', methods: ['GET'])]
    public function index(CategoryRecipeRepository $categoryRecipeRepository): Response
    {
        return $this->render('admin/category_recipe/index.html.twig', [
            'category_recipes' => $categoryRecipeRepository->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param CategoryRecipeRepository $categoryRecipeRepository
     * @return Response
     */
    #[Route('/new', name: 'app_category_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryRecipeRepository $categoryRecipeRepository): Response
    {
        $categoryRecipe = new CategoryRecipe();
        $form = $this->createForm(CategoryRecipeType::class, $categoryRecipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRecipeRepository->save($categoryRecipe, true);

            return $this->redirectToRoute('app_category_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category_recipe/new.html.twig', [
            'category_recipe' => $categoryRecipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param CategoryRecipe $categoryRecipe
     * @return Response
     */
    #[Route('/{id}', name: 'app_category_recipe_show', methods: ['GET'])]
    public function show(CategoryRecipe $categoryRecipe): Response
    {
        return $this->render('admin/category_recipe/show.html.twig', [
            'category_recipe' => $categoryRecipe,
        ]);
    }

    /**
     * @param Request $request
     * @param CategoryRecipe $categoryRecipe
     * @param CategoryRecipeRepository $categoryRecipeRepository
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_category_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoryRecipe $categoryRecipe, CategoryRecipeRepository $categoryRecipeRepository): Response
    {
        $form = $this->createForm(CategoryRecipeType::class, $categoryRecipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRecipeRepository->save($categoryRecipe, true);

            return $this->redirectToRoute('app_category_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category_recipe/edit.html.twig', [
            'category_recipe' => $categoryRecipe,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param CategoryRecipe $categoryRecipe
     * @param CategoryRecipeRepository $categoryRecipeRepository
     * @return Response
     */
    #[Route('/{id}', name: 'app_category_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, CategoryRecipe $categoryRecipe, CategoryRecipeRepository $categoryRecipeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryRecipe->getId(), $request->request->get('_token'))) {
            $categoryRecipeRepository->remove($categoryRecipe, true);
        }

        return $this->redirectToRoute('app_category_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
