<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Service\MailjetService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{

    #[Route('/admin/contact/{id}', name: 'admin_contact_show')]
public function showContact(Contact $contact, ManagerRegistry $doctrine): Response
{
    $entityManager = $doctrine->getManager();

    // Marquer le contact comme lu s'il n'est pas encore défini
    if ($contact->isRead() === null) {
        $contact->setIsRead(false);
        $entityManager->flush();
    }

    return $this->render('admin/contact/show.html.twig', [
        'contact' => $contact,
    ]);
}


#[Route('/contact', name: 'contact')]
public function contact(Request $request, ManagerRegistry $doctrine,MailjetService $mailjetService, MailerInterface $mailer, ValidatorInterface $validator): Response
{
    $contact = new Contact();
    $form = $this->createForm(ContactType::class, $contact);

    $form->handleRequest($request);

    $errorMessages = [];

    if ($form->isSubmitted()) {

        // Validation personnalisée via switch-case pour chaque champ
        switch (true) {
            // Validation pour le nom (lettres seulement)
            case !preg_match('/^[a-zA-Z\s]+$/', $contact->getName()):
                $errorMessages[] = 'Le nom ne doit contenir que des lettres.';
                break;

            // Validation pour l'email
            case !filter_var($contact->getEmail(), FILTER_VALIDATE_EMAIL):
                $errorMessages[] = 'Veuillez entrer une adresse email valide.';
                break;

            // Validation pour le numéro de téléphone (chiffres, espaces, +, -)
            case !preg_match('/^[0-9\s\+\-]+$/', $contact->getPhoneNumber()):
                $errorMessages[] = 'Le numéro de téléphone ne doit contenir que des chiffres, des espaces, "+" ou "-".';
                break;

            // Validation pour le message (ne doit pas être vide)
            case empty($contact->getMessage()):
                $errorMessages[] = 'Le message ne doit pas être vide.';
                break;

            // Default case pour passer à la validation suivante
            default:
                break;
        }

        // Si des erreurs sont trouvées, les afficher avant soumission
        if (!empty($errorMessages)) {
            // Ajout des messages d'erreur avec addFlash
            foreach ($errorMessages as $message) {
                $this->addFlash('danger', $message);
                
            }
        } else {
            // Sauvegarde du message dans la base de données
            $entityManager = $doctrine->getManager();
            $entityManager->persist($contact);
            $entityManager->flush();

            // Envoi de l'email de confirmation au client
            $clientHtmlContent = $this->renderView('emails/contact_confirmation.html.twig', [
                'contact' => $contact,
            ]);

            try {
                $mailjetService->sendEmail(
                    $contact->getEmail(),
                    $contact->getName(), // Assurez-vous que la méthode getName() existe
                    'Confirmation de votre message',
                    $clientHtmlContent
                );
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email de confirmation : ' . $e->getMessage());
            }

            // Envoi de l'email de notification à l'administrateur
            $adminHtmlContent = $this->renderView('emails/admin_contact_notification.html.twig', [
                'contact' => $contact,
            ]);

            try {
                $mailjetService->sendEmail(
                    'lepharaon09@hotmail.fr',
                    'Administrateur',
                    'Nouveau message de contact',
                    $adminHtmlContent
                );
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'envoi de l\'email à l\'administrateur : ' . $e->getMessage());
            }

            // Message flash de succès
            $this->addFlash('success', 'Votre message a été envoyé avec succès. Un email de confirmation vous a été envoyé.');

            // Redirection après le succès
            return $this->redirectToRoute('contact');
        }
    }

    return $this->render('contact/index.html.twig', [
        'contactForm' => $form->createView(),
    ]);
}
}
