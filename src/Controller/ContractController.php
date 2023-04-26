<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
 use Symfony\Component\Form\FormTypeInterface;

class ContractController extends AbstractController
{
    #[Route('/contract', name: 'app_contract')]
    public function index(): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class,$contact);
        return $this->render('contract/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
