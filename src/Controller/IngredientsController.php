<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientsController extends AbstractController
{

    public function __construct(
        private IngredientRepository $repo,
        private EntityManagerInterface $em,
    ) {
    }

    #[Route('/ingredients', name: 'app_ingredients')]
    public function index(PaginatorInterface $paginator, Request $request): Response
    {

        $ingredients  = $paginator->paginate(
            $this->repo->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('pages/ingredients/index.html.twig', [
            'ingredients' => $ingredients,
        ]);
    }

    #[Route('/ingredients/new', name: 'app_ingredients_new')]
    public function new(Request $request): Response
    {
        $ingredient = new Ingredient();

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();
            $this->em->persist($ingredient);
            $this->em->flush();
            $this->addFlash('success', "L'ingrédient a bien été ajouté");
            return $this->redirectToRoute('app_ingredients');
        }

        return $this->render('pages/ingredients/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/ingredients/update/{id}', name: 'app_ingredients_update')]
    public function update(Ingredient $ingredient, Request $request): Response
    {

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();
            $this->em->persist($ingredient);
            $this->em->flush();
            $this->addFlash('success', "L'ingrédient N°{$ingredient->getId()} a bien été modifié");
            return $this->redirectToRoute('app_ingredients');
        }
        
        return $this->render('pages/ingredients/update.html.twig', [
            'formUpdate' => $form->createView(),
            'ingredient' => $ingredient
        ]);
    }

    #[Route('/ingredients/delete/{id}', name: 'app_ingredient_delete')]
    public function delete($id): Response
    {
        $ingredient = $this->repo->find($id);

        if($ingredient !== null){ 
            $this->em->remove($ingredient);
            $this->em->flush();
            $this->addFlash('success', "l'ingredient {$ingredient->getName()} a été supprimé avec succès ");
        }
        return $this->redirectToRoute('app_ingredients');
    }
}
