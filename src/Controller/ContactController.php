<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    // Page de contact de BnB
    #[Route('/contact', name: 'contact')]
    public function contact(
        Request $request, // ici on injecte la classe Request dans la méthode
        MailerInterface $mailer // ici on injecte la classe MailerInterface dans la méthode
    ): Response {
        $form = $this->createForm(ContactType::class); // ici on charge le formulaire ContactType
        $form->handleRequest($request); // ici on traite la requête
        
        /**
         * Traitement du formulaire
         * Si le formulaire est soumis et qu'il est valide
         * alors on récupère les données du formulaire
         * et on les envoie par mail (pour le test on "dump and die" )
         */
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData(); // On récupère les données du formulaire

            $email = (new Email()) // On crée un nouvel objet Email
            ->from($data['email']) // Expéditeur
            ->to('contact@bnb.fr') // Destinataire
            ->subject($data['subject']) // Objet
            ->text($data['message']) // Message au format texte
            ->html(
                '<p>Nouveau message de ' . $data['firstname'] .' '. $data['lastname'] . ' :</p>' .
                '<p>' . $data['message'] . '</p>'
            ); // Message au format HTML

        $mailer->send($email); // On envoie le mail
        }

        return $this->render('page/contact.html.twig', [
            'contactForm' => $form,
        ]);
    }
}
