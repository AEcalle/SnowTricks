<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\ForgottenPasswordType;
use App\Form\ResetPassWordType;
use App\Repository\UserRepository;
use App\Service\MailerSender;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class ResetPasswordController extends AbstractController
{
    #[Route('/forgottenPassword', name: 'forgotten_password')]
    public function index(Request $request, UserRepository $userRepository, MailerSender $mailerSender): Response
    {
        $form = $this->createForm(ForgottenPasswordType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['username' => $form->get('username')->getData()]);

            if (!$user) {
                $this->addFlash('notice', 'Unknown username.');

                return $this->redirectToRoute('forgotten_password');
            }
            $user->setNewPasswordToken(Uuid::v4());
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $mailerSender->sendEmailResetPassword($user);
            $this->addFlash('notice', 'An email has been sent. Please click on the link in the email.');

            return $this->redirectToRoute('forgotten_password');
        }

        return $this->renderForm('reset_password/forgot.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/reset-password/{newPasswordToken}', name: 'reset_password')]
    public function reset(User $user, Request $request, UserPasswordHasherInterface $userPasswordHasherInterface): Response
    {
        $form = $this->createForm(ResetPassWordType::class)->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasherInterface->hashPassword(
                        $user,
                        $form->get('password')->getData()
                )
            );
            $user->setNewPasswordToken(null);

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('notice', 'Your password has been updated');

            return $this->redirectToRoute('home');
        }

        return $this->renderForm('reset_password/reset.html.twig', [
            'form' => $form,
        ]);
    }
}
