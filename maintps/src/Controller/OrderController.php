<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Form\SearchOrderForm;
use App\Data\SearchOrder;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use PHP_CodeSniffer\Tokenizers\JS;
use PHPUnit\Util\Json;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     */
    public function orderIndex(OrderRepository $orderRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $data = $orderRepository->inProgressOrder();
        $orders = $paginator->paginate(
            $data,  // Données à paginer
            $request->query->getInt('page', 1),  // Numéro page en cours, 1 par défaut
            15   // Nombre de commandes/page
        );

        return $this->render('order/default.html.twig', [
            'orders'    =>  $orders
        ]);
    }

    /**
     * @Route("/order/list", name="order_list")
     * @Security("is_granted('ROLE_USER')")
     */
    public function index(Request $request) : Response
    {
        $data = new SearchOrder();
        $data->page = $request->get('page', 1);
        $form = $this->createForm(SearchOrderForm::class, $data);
        $form->handleRequest($request);
        $orders = $this->orderRepository->findSearch($data);
        if($request->get('ajax')){
            return new JsonResponse([
                'content'   =>  $this->renderView('order/_orders.html.twig', ['orders' => $orders]),
                'sorting'   =>  $this->renderView('order/_sorting.html.twig', ['orders' => $orders]),
                'pagination'   =>  $this->renderView('order/_pagination.html.twig', ['orders' => $orders]),
            ]);
        }
        return $this->render('order/list.html.twig', [
            'orders'    =>  $orders,
            'form'      =>  $form->createView()
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
        if ($form->isSubmitted() && $form->isValid()) {
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
