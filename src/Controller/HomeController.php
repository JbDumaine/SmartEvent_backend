<?php


namespace App\Controller;


use App\Entity\Event;
use App\Entity\Item;
use App\Repository\ItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     *
     * @IsGranted("ROLE_USER")
     */
    public function index(): Response
    {
        /*$encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $event = new Event();
        $event->setTitle('Mariage');
        $date = new \DateTime('01/01/2019');
        $event->setEventDate($date);
        $event->setDescription('Mariage du roi');
        $event->setAddress('43 rue de l\'or');

        $jsonEvent = $serializer->serialize($event, 'json');*/

        return $this->json('connecté');
    }

    /**
     * @Route("/post", name="post", methods={"POST"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function post(Request $request, EntityManagerInterface $em): Response
    {
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers, $encoders);

        $data = $request->getContent();

        $item = $serializer->deserialize($data, Item::class, 'json');
        $em->persist($item);
        $em->flush();

        return $this->json('ok');
    }

    /**
     * @Route("/get/{id}", name="get", methods={"GET"})
     *
     * @IsGranted("ROLE_USER")
     */
    public function getItem(int $id, ItemRepository $itemRepository): Response
    {
        $item = $itemRepository->find($id);

        return $this->json($item);
    }

    /**
     * @Route("/email", name="email_test")
     *
     */
    public function sendEmail(MailerInterface $mailer): Response
    {
        $email = (new Email())
            ->from('test@gmail.com')
            ->to('rmn976@hotmail.fr')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject('Test GenjyStudio!')
            ->text('Coucou mon coeur!');
        try {
            $mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            return $this->json($e->getMessage());
        }

        return $this->json('Email send !');
    }
}