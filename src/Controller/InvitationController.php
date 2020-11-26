<?php


namespace App\Controller;


use App\Entity\Invitation;
use App\Repository\EventRepository;
use App\Repository\InvitationRepository;
use App\Repository\StatusRepository;
use App\Repository\UserRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InvitationController extends AbstractController
{
    /**
     * @Route("/api/invitation/event/{id}", name="api_invitation_index", methods={"GET"})
     */
    public function all(int $id, InvitationRepository $invitationRepository, EventRepository $eventRepository): Response
    {
        if (!$event = $eventRepository->findOneBy([
            'id' => $id,
            'organizer' => $this->getUser()->getId()
        ])) {
            return $this->json([
                'status' => 404,
                'message' => 'Event not found'
            ], 404);
        }
        if (!$invitations = $invitationRepository->findBy(['event' => $event])) {
            return $this->json([
                'status' => 204,
                'message' => 'No invitations'
            ], 204);
        }
        return $this->json($invitations, 200, [], ['groups' => 'invitation:read']);
    }

    /**
     * @Route("/invitation/{token}", name="api_invitation_get_invitation", methods={"GET"})
     */
    public function getEventByToken(string $token, InvitationRepository $invitationRepository): Response
    {
        if (!$invitation = $invitationRepository->findBy(['invitationToken' => $token])) {
            return $this->json([
                'status' => 404,
                'message' => 'Event not found'
            ], 404);
        }

        return $this->json($invitation, 200, [], ['groups' => 'invitation:read']);
    }

    /**
     * @Route("/api/invitation", name="api_invitation_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EventRepository $eventRepository, UserRepository $userRepository, StatusRepository $statusRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator, MailerInterface $mailer)
    {

        $jsonGet = $request->getContent();

        try {
            $invitation = $serializer->deserialize($jsonGet, Invitation::class, 'json');

            $eventId = json_decode($jsonGet)->event;

            $userEmail = json_decode($jsonGet)->email;

            $key = 'example_key';
            $payload = [
                "user" => $userEmail,
                "event" => $eventId
            ];

            $invitationToken = JWT::encode($payload, $key);

            $invitation->setEvent($eventRepository->find($eventId));

            if (isset(json_decode($userId = $jsonGet)->user)) {
                $invitation->setGuest($userRepository->find($userId));
            }

            $invitation->setStatus($statusRepository->find(1));

            $invitation->setInvitationToken($invitationToken);

            $errors = $validator->validate($invitation);

            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }

            try {
                $entityManager->persist($invitation);
                $entityManager->flush();
            } catch (UniqueConstraintViolationException $e) {
                return $this->json([
                    'status' => 400,
                    'message' => $e->getMessage()
                ], 400);
            }

            $email = (new Email())
                ->from('genjystudio@gmail.com')
                ->to($userEmail)
                ->subject('Test GenjyStudio!')
                ->text('Voici le lien : http://smartevent-api.tk/invitation/' . $invitationToken);
            try {
                $mailer->send($email);
            } catch (TransportExceptionInterface $e) {
                return $this->json($e->getMessage());
            }

            return $this->json($invitation, 201, [], ['groups' => 'invitation:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }

    }


    /**
     * @Route("/api/invitation/{token}", name="api_invitation_update", methods={"PUT"})
     */
    public function update(string $token, Request $request, InvitationRepository $invitationRepository, StatusRepository $statusRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        if (!$invitation = $invitationRepository->findOneBy(['invitationToken' => $token])) {
            return $this->json([
                'status' => 404,
                'message' => 'Event not found'
            ], 404);
        }

        $jsonGet = $request->getContent();

        try {
            $invitation->setStatus($statusRepository->find(json_decode($jsonGet)->status));

            $errors = $validator->validate($invitation);

            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $entityManager->persist($invitation);
            $entityManager->flush();

            return $this->json($invitation, 200, [], ['groups' => 'invitation:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }

    }

    /**
     * @Route("/api/invitation/{token}", name="api_invitation_delete", methods={"DELETE"})
     */
    public function delete(string $token, InvitationRepository $invitationRepository, EntityManagerInterface $entityManager)
    {

        if (!$invitation = $invitationRepository->findOneBy(['invitationToken' => $token])) {
            return $this->json([
                'status' => 404,
                'message' => 'Event not found'
            ], 404);
        }

        $entityManager->remove($invitation);
        $entityManager->flush();

        return $this->json('Invitation deleted', 200);
    }

    // Route pour la liste des invitations d'un utilisateur
    /*public function all(InvitationRepository $invitationRepository): Response
    {
        // return $this->json($invitationRepository->findBy(['guest' => $this->getUser()->getId()]), 200, [], ['groups' => 'invitation:read']);
        return $this->json($invitationRepository->findAll(), 200, [], ['groups' => 'invitation:read']);
    }*/
}