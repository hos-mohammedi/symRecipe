<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecettesController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $em,
        private RecetteRepository $recetteRepo,
        private PaginatorInterface $paginator
    ) {
    }

    #[Route('/recettes', name: 'app_recettes', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $recettes = $this->paginator->paginate(
            $this->recetteRepo->findAll(),
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('pages/recettes/index.html.twig', [
            'recettes' => $recettes,
        ]);
    }

    #[Route('/recettes/add', name: 'recettes.add')]
    public function add(Request $request): Response
    {
        $recette = new Recette();

        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($recette);
            $this->em->flush();
            $this->addFlash('success', "La recette {$recette->getName()} a bien été ajoutée ");
            return $this->redirectToRoute('app_recettes');
        }

        return $this->render('pages/recettes/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/recettes/edit/{id}', name: 'recettes.edit', methods: ['GET', 'POST'])]
    public function edit(Recette $recette, Request $request): Response
    {   
        $form = $this->createForm(RecetteType::class, $recette);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->em->persist($recette);
            $this->em->flush();
            $this->addFlash('success', "La recette {$recette->getName()} a bien été modifiée ");
            return $this->redirectToRoute('app_recettes');
        }

        return $this->render('pages/recettes/edit.html.twig', [
            'form' => $form->createView(),
            'recette' => $recette
        ]);
    }

    #[Route('/recettes/delete/{id}', name: 'recettes.delete', methods: ['GET'])]
    public function delete(Recette $recette): Response
    {
        if($recette){
            $this->em->remove($recette);
            $this->em->flush();
            $this->addFlash('success', "La recette {$recette->getName()} a bien été supprimée");
        }
        return $this->redirectToRoute('app_recettes');
    }
}
