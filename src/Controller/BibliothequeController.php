<?php

namespace App\Controller;

use App\Entity\Bibliotheque;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\BibliothequeRepository;
use App\Repository\LivreRepository;
use App\Form\BibliothequeType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class BibliothequeController extends AbstractController
{


    #[Route('/bibliotheque/search', name: 'bibliotheque_search')]
public function search(Request $request, LivreRepository $livreRepository): Response
{
    $query = $request->query->get('query');
    $livres = $livreRepository->search($query);

    return $this->render('bibliotheque/search_results.html.twig', [
        'livres' => $livres,
        'query' => $query
    ]);
}

    #[Route('/', name: 'app_bibliotheque')]
    public function index(): Response
    {
        $response = $this->render('bibliotheque/index.html.twig', [
            'controller_name' => 'BibliothequeController',
        ]);
$response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');
$response->headers->set('Pragma', 'no-cache');
$response->headers->set('Expires', '0');
return $response;
    }


    #[Route('/bibliotheque/list', name: 'bibliotheque_list')]
    public function list(BibliothequeRepository $bibliothequeRepository): Response
    {
        $user = $this->getUser();
        
        if ($this->isGranted('ROLE_LIBRARIAN') and $this->isGranted('ROLE_CLIENT')) {
            $bibliotheques = $bibliothequeRepository->findAllByUser($user);
        } else {
            $bibliotheques = $bibliothequeRepository->findAll();
        }
    
        return $this->render('bibliotheque/list.html.twig', [
            'bibliotheques' => $bibliotheques, 'user'=>$user
        ]);
    }
    

    #[Route('/bibliotheque/new', name: 'bibliotheque_new')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $bibliotheque = new Bibliotheque();
    $form = $this->createForm(BibliothequeType::class, $bibliotheque);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager->persist($bibliotheque);
        $entityManager->flush();
        

        return $this->redirectToRoute('bibliotheque_list');
    }

    return $this->render('bibliotheque/new.html.twig', [
        'form' => $form->createView(),
    ]);
}


#[Route('/bibliotheque/{id}', name: 'bibliotheque_show')]
public function show(Bibliotheque $bibliotheque): Response
{
 
    return $this->render('bibliotheque/show.html.twig', [
        'bibliotheque' => $bibliotheque,
    ]);
}

#[Route('/bibliotheque/delete/{id}', name: 'bibliotheque_delete')]
public function delete(EntityManager $entityManager,Bibliotheque $bibliotheque): Response
{
    $entityManager->remove($bibliotheque);
    $entityManager->flush();

    return $this->redirectToRoute('bibliotheque_list');
}




}
