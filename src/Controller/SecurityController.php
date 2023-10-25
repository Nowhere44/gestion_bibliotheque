<?php
namespace App\Controller;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use App\Form\RegistrationFormType;




class SecurityController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
    
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
    
        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
    #[Route('/logout', name: 'logout', methods: ['GET'])]
    public function logout()
    {
        throw new \Exception('Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion du pare-feu');
    }
    #[Route('/register', name: 'app_register')]
public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
{
    $user = new User();
    $user->setRoles(['ROLE_CLIENT']);
    $form = $this->createForm(RegistrationFormType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
     
        $user->setPassword($passwordHasher->hashPassword($user, $user->getPassword()));

   
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('login');
    }

    return $this->render('security/register.html.twig', [
        'registrationForm' => $form->createView(),
    ]);
}

#[Route('/dispatch', name: 'dispatch')]
public function dispatch(): Response
{
    if ($this->getUser()) {
        if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('admin');
        } elseif (in_array('ROLE_LIBRARIAN', $this->getUser()->getRoles())) {
            return $this->redirectToRoute('bibliotheque_list'); 
        } else {
            return $this->redirectToRoute('bibliotheque_list');
        }
    }else{
        return $this->redirectToRoute('login');
    }
}


}
