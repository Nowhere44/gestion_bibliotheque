<?php 

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Bibliotheque;
use App\Form\BibliothequeType;
use App\Repository\BibliothequeRepository;

#[Route("/librarian")]
class LibrarianController extends AbstractController
{
    #[Route("/edit", name: "librarian_edit_bibliotheque")]
    public function edit(Request $request, EntityManagerInterface $entityManager, BibliothequeRepository $bibliothequeRepository)
    {
        $user = $this->getUser();
        $bibliotheque = $bibliothequeRepository->findByUser($user);
    
        if (!$bibliotheque) {
            throw $this->createNotFoundException('Vous n\'avez pas de bibliothèque associée à votre compte.');
        }

        $form = $this->createForm(BibliothequeType::class, $bibliotheque);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Bibliothèque mise à jour avec succès !');
            return $this->redirectToRoute('bibliotheque_list');
        }

        return $this->render('librarian/edit.html.twig', [
            'form' => $form->createView(),'bibliotheque'=>$bibliotheque
        ]);
    }

    #[Route("/delete/{id}", name: "librarian_delete_bibliotheque")]
    public function delete(Bibliotheque $bibliotheque, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($bibliotheque);
        $entityManager->flush();

        $this->addFlash('success', 'Bibliothèque supprimée avec succès !');
        return $this->redirectToRoute('bibliotheque_list');
    }
}
