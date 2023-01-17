<?php

namespace App\Controller\admin;

use App\Entity\CategoryIngredient;
use App\Form\CategoryIngredientType;
use App\Repository\CategoryIngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Admin Crud For Ingredient Categories
 */
#[Route('admin/category/ingredient')]
class CategoryIngredientController extends AbstractController
{
    /**
     * @param CategoryIngredientRepository $categoryIngredientRepository
     * @return Response
     */
    #[Route('/', name: 'app_category_ingredient_index', methods: ['GET'])]
    public function index(CategoryIngredientRepository $categoryIngredientRepository): Response
    {
        return $this->render('admin/category_ingredient/index.html.twig', [
            'category_ingredients' => $categoryIngredientRepository->findAll(),
        ]);
    }

    /**
     * @param Request $request
     * @param CategoryIngredientRepository $categoryIngredientRepository
     * @return Response
     */
    #[Route('/new', name: 'app_category_ingredient_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryIngredientRepository $categoryIngredientRepository): Response
    {
        $categoryIngredient = new CategoryIngredient();
        $form = $this->createForm(CategoryIngredientType::class, $categoryIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryIngredient->setUpdatedAt(new \DateTimeImmutable());
            $categoryIngredientRepository->save($categoryIngredient, true);

            return $this->redirectToRoute('app_category_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category_ingredient/new.html.twig', [
            'category_ingredient' => $categoryIngredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param CategoryIngredient $categoryIngredient
     * @return Response
     */
    #[Route('/{id}', name: 'app_category_ingredient_show', methods: ['GET'])]
    public function show(CategoryIngredient $categoryIngredient): Response
    {
        return $this->render('admin/category_ingredient/show.html.twig', [
            'category_ingredient' => $categoryIngredient,
        ]);
    }

    /**
     * @param Request $request
     * @param CategoryIngredient $categoryIngredient
     * @param CategoryIngredientRepository $categoryIngredientRepository
     * @return Response
     */
    #[Route('/{id}/edit', name: 'app_category_ingredient_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoryIngredient $categoryIngredient, CategoryIngredientRepository $categoryIngredientRepository): Response
    {
        $form = $this->createForm(CategoryIngredientType::class, $categoryIngredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryIngredientRepository->save($categoryIngredient, true);

            return $this->redirectToRoute('app_category_ingredient_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/category_ingredient/edit.html.twig', [
            'category_ingredient' => $categoryIngredient,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param CategoryIngredient $categoryIngredient
     * @param CategoryIngredientRepository $categoryIngredientRepository
     * @return Response
     */
    #[Route('/{id}', name: 'app_category_ingredient_delete', methods: ['POST'])]
    public function delete(Request $request, CategoryIngredient $categoryIngredient, CategoryIngredientRepository $categoryIngredientRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryIngredient->getId(), $request->request->get('_token'))) {
            $categoryIngredientRepository->remove($categoryIngredient, true);
        }

        return $this->redirectToRoute('app_category_ingredient_index', [], Response::HTTP_SEE_OTHER);
    }
}
