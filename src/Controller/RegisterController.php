<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ResgisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    // Injection de dépendance de doctrine(quand register controller est construit entity manager est instancié et embarque la request le password interface doctine  pour exécuter le code qui est en dessous  ) 
    public function __construct(EntityManagerInterface $entityManager)
        {
           $this->entityManager = $entityManager;
        }

    #[Route('/inscription', name: 'app_register')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher): Response
    {


        // Instanciation de la Class User 
        $user = new User();

        // Utilise la method CreateForm pour faire le formulaire en injectant le register type
        $form = $this->createForm(ResgisterType::class,$user);
        // method qui permet d'analyser la requete du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            // si le formulaire est valide  injecte les données du formulaire dans L'entiter USer
            $user = $form->getData();


            // pour crypter le mot de passe
            $password = $passwordHasher->hashPassword($user, $user->getPassword());

            // Définit le mot de passe crypté
            $user->setPassword($password);

            // on fige la donné et on l'envoie en base de données 
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            

        }




        return $this->render('register/index.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
