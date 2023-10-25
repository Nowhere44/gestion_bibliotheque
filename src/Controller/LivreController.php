<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LivreRepository;

class LivreController extends AbstractController
{
    #[Route('/livre', name: 'app_livre')]
    public function index(): Response
    {
        return $this->render('livre/index.html.twig', [
            'controller_name' => 'LivreController',
        ]);
    }

    #[Route('/livre/list', name: 'app_livre_list')]
    public function list(LivreRepository $livreRepository): Response
    {
        $livres = $livreRepository->getAllLivres();
    
        return $this->render('livre/list.html.twig', [
            'livres' => $livres,
        ]);
    }


    #[Route('/livre/new', name: 'livre_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($livre);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_livre_list');
        }
    
        return $this->render('livre/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/livre/edit/{id}', name: 'livre_edit')]
public function edit(Request $request, EntityManagerInterface $entityManager, Livre $livre): Response
{
    $form = $this->createForm(LivreType::class, $livre);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->flush();

        return $this->redirectToRoute('app_livre_list');
    }

    return $this->render('livre/edit.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/livre/delete/{id}', name: 'livre_delete')]
public function delete(EntityManagerInterface $entityManager, Livre $livre): Response
{
    $entityManager->remove($livre);
    $entityManager->flush();

    return $this->redirectToRoute('app_livre_list');
}


}
