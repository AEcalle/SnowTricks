<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Service\MailerSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasherInterface, 
        MailerSender $mailerSender
    ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
            $userPasswordHasherInterface->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setToken(str_replace('.', '', uniqid((string) rand(), true)));

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
            $mailerSender->sendEmailValidation($user);

            $this->addFlash('notice', 'An email with a link to activate your account
            has been sent to '.$user->getEmail().'.');

            return $this->redirectToRoute('login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/validation/{id}/{token}', name: 'registration_validation')]
    public function validation(User $user, string $token): Response
    {
        if ($user->getToken() !== $token) {
            $this->addFlash('notice', 'This link is not valid');

            return $this->redirectToRoute('app_register');
        }
        $user->setToken(null);
        $user->setCreatedAt(new \DateTimeImmutable());
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('notice', 'Your account is activated.');

        return $this->redirectToRoute('login');
    }
}
