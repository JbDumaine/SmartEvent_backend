<?php


namespace App\Controller;


use App\Entity\EventsItems;
use App\Repository\EventRepository;
use App\Repository\EventsItemsRepository;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ItemController extends AbstractController
{
    /**
     * @Route("/api/item/event", name="api_add_item", methods={"POST"})
     */
    public function add(Request $request, EventRepository $eventRepository, ItemRepository $itemRepository, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $jsonGet = $request->getContent();

        try {
            $eventId = json_decode($jsonGet)->event;

            $itemId = json_decode($jsonGet)->item;

            $eventItems = new EventsItems();

            $eventItems->setEvent($eventRepository->find($eventId));

            $eventItems->setItem($itemRepository->find($itemId));

            $eventItems->setIsChecked(false);

            $errors = $validator->validate($eventItems);

            if(count($errors) > 0) {
                return $this->json($errors, 400);
            }

            $entityManager->persist($eventItems);
            $entityManager->flush();
        } catch (NotEncodableValueException $e) {
        return $this->json([
            'status' => 400,
            'message' => $e->getMessage()
        ], 400);
    }



        return $this->json($eventItems, 200, [], ['groups' => 'eventItem:read']);
    }

    /**
     * @Route("/api/item/event/{id}", name="api_item_index", methods={"GET"})
     */
    public function all(int $id, EventsItemsRepository $eventsItemsRepository, EventRepository $eventRepository): Response
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
        if (!$items = $eventsItemsRepository->findBy(['event' => $event])) {
            return $this->json([
                'status' => 204,
                'message' => 'No items'
            ], 204);
        }
        return $this->json($items, 200, [], ['groups' => 'eventItem:read']);
    }

    /**
     * @Route("/api/item/event/{id}", name="api_item_update", methods={"PUT"})
     */
    public function update(int $id, EventsItemsRepository $eventsItemsRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$item = $eventsItemsRepository->find($id)) {
            return $this->json([
                'status' => 404,
                'message' => 'Items not found'
            ], 404);
        }

        if (!$item->getIsChecked()) {
            $item->setIsChecked(true);
        }

        $entityManager->persist($item);
        $entityManager->flush();
        return $this->json($item, 200, [], ['groups' => 'eventItem:read']);
    }

    /**
     * @Route("/api/item/event/{id}", name="api_item_delete", methods={"DELETE"})
     */
    public function delete(int $id, EventsItemsRepository $eventsItemsRepository, EntityManagerInterface $entityManager): Response
    {
        if (!$item = $eventsItemsRepository->find($id)) {
            return $this->json([
                'status' => 404,
                'message' => 'Items not found'
            ], 404);
        }

        $entityManager->remove($item);
        $entityManager->flush();

        return $this->json('Item deleted', 200, []);
    }
}