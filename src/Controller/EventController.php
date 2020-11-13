<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EventController extends AbstractController
{
    /**
     * @Route("/api/event", name="api_event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository): Response
    {
        return $this->json($eventRepository->findAll(), 200, [], ['groups' => 'event:read']);
    }

    /**
     * @Route("api/event", name="api_event_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, UserRepository $userRepository, EventTypeRepository $eventTypeRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $jsonGet = $request->getContent();

        try {
            $event = $serializer->deserialize($jsonGet, Event::class, 'json');

            $organizer = $userRepository->find(json_decode($jsonGet)->organizer);
            $type = $eventTypeRepository->find(json_decode($jsonGet)->type);
            $event->setOrganizer($organizer);
            $event->setType($type);

            $errors = $validator->validate($event);

            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->json($event, 201, [], ['groups' => 'event:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }

    }
}
