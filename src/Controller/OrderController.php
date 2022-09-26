<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart, Request $request): Response
    {
       
        if (!$this->getUser()->getAddresses()->getValues()) {
            // On le redirige vers la page d'ajout d'adresse
            return $this->redirectToRoute('app_account_address_add');
        }
       
        $form=$this->createForm(OrderType::class, null,[
            'user'=>$this->getUser()
        ]);

        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            // dd($form->getData());
        }


        
        return $this->render('order/index.html.twig', [
            'form'=>$form->createView(),
            'cart' => $cart->getFull()
        ]);
    }

    // #[Route('/commande/recapitulatif', name: 'app_order_recap', methods: "POST")]
    // public function add(Cart $cart, Request $request): Response
    // {
    //     $form = $this->createForm(OrderType::class, null, [
    //         'user' => $this->getUser()
    //     ]);

    //     // Ecoute la requête
    //     $form->handleRequest($request);

    //     // SI le formulaire est soumis ET le formulaire est valide ALORS
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $order = new Order();
    //         $date = new DateTime();

    //         // Récupère le champ du formulaire
    //         $carriers = $form->get('carriers')->getData();
    //         $delivery = $form->get('addresses')->getData();

    //         $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
    //         $delivery_content .= '<br>'.$delivery->getPhone();

    //         if ($delivery->getCompagny()) {
    //             $delivery_content .= '<br>'.$delivery->getCompagny();
    //         }

    //         $delivery_content .= '<br>'.$delivery->getAddress();
    //         $delivery_content .= '<br>'.$delivery->getPostal().' '.$delivery->getCity();
    //         $delivery_content .= '<br>'.$delivery->getCountry();

    //         $order = new Order();
    //         $reference = $date->format('dmY').'-'.uniqid();
    //         $order->setReference($reference);

    //         // dd($delivery_content);

            

    //         // Enregistrer ma commande Order()
    //         $order->setUser($this->getUser());

    //         $order->setCreatedAt($date);

    //         // Définit le nom de carriers
    //         $order->setCarrierName($carriers->getName());

    //         // Définit le prix de carriers
    //         $order->setCarrierPrice($carriers->getPrice());

    //         $order->setDelivery($delivery_content);

    //         $order->setIsPaid(0);

    //         // Fige la data
    //         $this->entityManager->persist($order);

    //         // Pour chaque produit que j'ai dans mon panier
    //         // $products_for_stripe = [];

    //         foreach($cart->getFull() as $product){

    //             // Enregistrer mes produits OrderDetails()
    //             $orderDetails = new OrderDetails();
    //             $orderDetails->setMyOrder($order);
    //             $orderDetails->setProduct($product['product']->getName());
    //             $orderDetails->setQuantity($product['quantity']);
    //             $orderDetails->setPrice($product['product']->getPrice());
    //             $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
               
    //             // Fige la data
    //             $this->entityManager->persist($orderDetails);
                

    //         }
                
    //             $this->entityManager->flush();
    //             // dd($order);

    //             // STRIP fait le lien entre la banque et notre site :D

                
           
    //         return $this->render('order/add.html.twig', [
    //             'cart' => $cart->getFull(),
    //             'carrier' => $carriers,
    //             'delivery' => $delivery_content,
    //             'reference'=>$order->getReference()
                
    //         ]);
            
    //     }

    //     return $this->redirectToRoute('app_cart');
            
    // }
    
}
