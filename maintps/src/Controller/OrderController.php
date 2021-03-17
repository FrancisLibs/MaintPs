<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $orderRepository;
    private $manager;

    public function __construct(OrderRepository $orderRepository, EntityManagerInterface $manager)
    {
        $this->orderRepository = $orderRepository;
        $this->manager = $manager;
    }

    /**
     * @Route("/order", name="order")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(): Response
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    /**
     * @Route("/order/processed", name="order_in_progress")
     * @Security("is_granted('ROLE_USER')")
     */
    public function orderInProgress(): Response
    {
        $orders = $this->orderRepository->inProgressOrder();

        return $this->render('order/index.html.twig', [
            'orders'    => $orders,
        ]);
    }

    /**
     * @Route("/order/create", name="order_new")
     * @Security("is_granted('ROLE_USER')")
     */
    public function orderCreate(Request $request, EntityManagerInterface $manager): Response
    {
        $order = new Order();

        $form = $this->createForm(OrderType::class, $order);
        
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $order = $form->getData();
            $order->setuser($this->getUser());
            $order->setCreatedAt(new \DateTime('now'));

            $manager->persist($order);
            $order->setOrderNumber($this->orderRepository->findLastOrder()->getOrderNumber() + 1);
            $manager->persist($order);
            $manager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('order/create.html.twig', [
            'form'  => $form->createView(),
        ]);
    }

    /**
     * @Route("/order/{id}", name="order_show")
     * @Security("is_granted('ROLE_USER')")
     */
    public function show(Order $order): Response
    {
        return $this->render('order/show.html.twig', [
            'order'    => $order,
        ]);
    }
}
