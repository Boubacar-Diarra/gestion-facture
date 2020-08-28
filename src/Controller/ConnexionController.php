<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AddUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();

        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('home');
        }

        return $this->render('connexion/login.twig', ['error' => $error]);
    }
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {

    }

    /**
     * @Route("/add/user", name="add.user")
     * @param Request $request
     * @param UserPasswordEncoderInterface $encoder
     * @param SessionInterface $session
     * @return RedirectResponse|Response
     */
    public function addUser(Request $request, UserPasswordEncoderInterface $encoder, SessionInterface $session)
    {
        $manager = $this->getDoctrine()->getManager();
        $user = new User();
        $form = $this->createForm(AddUserType::class, $user);
        $form->add('enregistrer', SubmitType::class, ['attr' => ['class' => 'btn s-card']]);
        $form->handleRequest($request);
        if($form->isSubmitted() and $form->isValid())
        {
            #mettre en place une zone d'administration pour administrateur du site
            $user->setRoles(['ROLE_USER']);
            #$user->setRoles(['ROLE_ADMIN']);
            #
            $session->set("password", $user->getPassword());
            $session->set("email", $user->getEmail());
            #
            $hash = $encoder->encodePassword($user,$user->getPassword());
            $user->setPassword($hash);
            $manager->persist($user);
            $manager->flush();
            #
            return $this->redirectToRoute("home");
        }
        $this->addFlash("ajoutStatus", false);
        return $this->render('connexion/addUser.twig', ['form' => $form->createView()]);
    }
}
