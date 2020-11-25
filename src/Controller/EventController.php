<?php

namespace App\Controller;

use App\Entity\Event;
use App\Repository\EventRepository;
use App\Repository\EventTypeRepository;
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
    public function all(EventRepository $eventRepository): Response
    {
        // return $this->json($this->getUser()->getOrganizedEvents(), 200, [], ['groups' => 'event:read']);
        return $this->json($eventRepository->findAll(), 200, [], ['groups' => 'event:read']);
    }

    /**
     * @Route("/api/event/{id}", name="api_event_get_event", methods={"GET"})
     */
    public function getEvent(int $id, EventRepository $eventRepository): Response
    {
        if (!$event = $eventRepository->find($id)/*$eventRepository->findBy(
            ['id' => $id],
            ['organizer' => $this->getUser()->getId()]
        )*/) {
            return $this->json([
                'status' => 404,
                'message' => 'Event not found'
            ], 404);
        }

        return $this->json($event, 200, [], ['groups' => 'event:read']);
    }

    /**
     * @Route("/api/event", name="api_event_create", methods={"POST"})
     */
    public function create(Request $request, SerializerInterface $serializer, EventTypeRepository $eventTypeRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        $jsonGet = $request->getContent();

        try {
            $event = $serializer->deserialize($jsonGet, Event::class, 'json');

            $event->setOrganizer($this->getUser());
            $event->setType($eventTypeRepository->find(json_decode($jsonGet)->type));

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

    /**
     * @Route("/api/event/{id}", name="api_event_update", methods={"PUT"})
     */
    public function update(int $id, Request $request, SerializerInterface $serializer, EventRepository $eventRepository, EventTypeRepository $eventTypeRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {

        if (!$event = $eventRepository->find($id)/*$eventRepository->findBy(
            ['id' => $id],
            ['organizer' => $this->getUser()->getId()]
        )*/) {
            return $this->json([
                'status' => 404,
                'message' => 'Event not found'
            ], 404);
        }

        $jsonGet = $request->getContent();

        try {
            $newDataEvent = $serializer->deserialize($jsonGet, Event::class, 'json');
            $event->setTitle($newDataEvent->getTitle());
            $event->setEventDate($newDataEvent->getEventDate());
            $event->setDescription($newDataEvent->getDescription());
            $event->setAddress($newDataEvent->getAddress());
            $event->setType($eventTypeRepository->find(json_decode($jsonGet)->type));

            $errors = $validator->validate($event);

            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->json($event, 200, [], ['groups' => 'event:read']);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                'status' => 400,
                'message' => $e->getMessage()
            ], 400);
        }

    }

    /**
     * @Route("/api/event/{id}", name="api_event_delete", methods={"DELETE"})
     */
    public function delete(int $id, EventRepository $eventRepository, EntityManagerInterface $entityManager)
    {

        if (!$event = $eventRepository->find($id)/*$eventRepository->findBy(
            ['id' => $id],
            ['organizer' => $this->getUser()->getId()]
        )*/) {
            return $this->json([
                'status' => 404,
                'message' => 'Event not found'
            ], 404);
        }

        $entityManager->remove($event);
        $entityManager->flush();

        return $this->json($eventRepository->findAll(), 200, [], ['groups' => 'event:read']);
    }
}
