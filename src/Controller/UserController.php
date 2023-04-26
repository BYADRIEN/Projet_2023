<?php

namespace App\Controller;
use App\Entity\Users;
use App\form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/utilisateur/edition/{id}', name: 'user.edit')]
    public function edit(Users $users ): Response
    {
        if(!$this->getUser())
        {
            return $this->redirectToRoute('home');
        }
        if($this->getUser() !== $users)
        {
            return $this->redirectToRoute('app_logout');
        }

        $form = $this->createForm(UserType::class, $users);
        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
