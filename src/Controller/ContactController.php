<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactController extends AbstractController
{

    #[Route('/admin/contact/{id}', name: 'admin_contact_show')]
public function showContact(Contact $contact, ManagerRegistry $doctrine): Response
{
    $entityManager = $doctrine->getManager();
    $contact->setIsRead(true); // Marque comme lu
    $entityManager->flush();

    return $this->render('admin/contact/show.html.twig', [
        'contact' => $contact,
    ]);
}


    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, ManagerRegistry $doctrine, MailerInterface $mailer): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarde du message dans la base de données
            $entityManager = $doctrine->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            // Envoi de l'email de confirmation au client
            $clientEmail = (new Email())
                ->from('noreply@aubergegeorgio.com') // Remplacez par votre adresse email d'envoi
                ->to($contact->getEmail()) // Email du client
                ->subject('Confirmation de votre message')
                ->html($this->renderView('emails/contact_confirmation.html.twig', [
                    'contact' => $contact,
                ])
            );

            $mailer->send($clientEmail);

            // Envoi de l'email de notification à l'administrateur
            $adminEmail = (new Email())
                ->from('noreply@aubergegeorgio.com')
                ->to('admin@aubergegeorgio.com') // Remplacez par l'adresse email de l'administrateur
                ->subject('Nouveau message de contact')
                ->html($this->renderView('emails/admin_contact_notification.html.twig', [
                    'contact' => $contact,
                ]));

            $mailer->send($adminEmail);

            // Message flash et redirection
            $this->addFlash('success', 'Votre message a été envoyé avec succès. Un email de confirmation vous a été envoyé.');

            return $this->redirectToRoute('contact');
        }

        return $this->render('contact/index.html.twig', [
            'contactForm' => $form->createView(),
        ]);
    }
}
