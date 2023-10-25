<?php 
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Livre;
use App\Entity\Emprunt;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmpruntController extends AbstractController
{
    #[Route('/emprunt', name: 'app_emprunt')]
    public function index(): Response
    {
        return $this->render('emprunt/index.html.twig', [
            'controller_name' => 'EmpruntController',
        ]);
    }

    #[Route('/emprunter/livre/{id}', name: 'emprunter_livre')]
    public function emprunterLivre(Livre $livre, MailerInterface $mailer, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $user = $this->getUser();
        if (!$user instanceof User) {
            throw $this->createNotFoundException('Vous devez être connecté pour emprunter un livre.');
        }

        $emprunt = new Emprunt();
        $emprunt->setBorrower($user);
        $emprunt->setLivre($livre);
        $emprunt->setDateEmprunt(new \DateTime());
        $emprunt->setDateRetour((new \DateTime())->modify('+2 weeks'));


        $entityManager->persist($emprunt);
        $entityManager->flush();

        // Envoyer un e-mail de notification
        $email = (new Email())
            ->from('send@example.com')
            ->to($user->getEmail())
            ->subject('Nouvel emprunt')
            ->html($this->renderView('emails/emprunt.html.twig', ['livre' => $livre, 'user' => $user ,'emprunt'=>$emprunt]));

        $mailer->send($email);

        $this->addFlash('success', 'Livre emprunté avec succès !');
        return $this->redirectToRoute('bibliotheque_show', ['id' => $livre->getBibliotheque()->getId()]);
    }
}
