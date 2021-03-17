<?php

namespace App\Controller;

use App\Entity\DeliveryForm;
use App\Form\DeliveryFormType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DeliveryFormController extends AbstractController
{
    /**
     * @Route("/deliveryform/create/{id}", name="delivery_form_new")
     */
    public function create(int $id, Request $request, OrderRepository $orderRepository, EntityManagerInterface $manager): Response
    {
        $order = $orderRepository->findOneById($id);

        $deliveryForm = new DeliveryForm();

        $form = $this->createForm(DeliveryFormType::class, $deliveryForm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $deliveryForm->setOrder($order);
            $manager->persist($deliveryForm);
            $manager->flush();
            return $this->redirectToRoute('home');
        }
        return $this->render('delivery_Form/create.html.twig', [
            'form' => $form->createView(),
            'order' =>  $order,
        ]);
    }
}
