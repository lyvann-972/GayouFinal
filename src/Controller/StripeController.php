<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session', name: 'app_stripe_create-session')]
    
    
    public function index(Cart $cart): Response
    {
        $products_for_stripe=[];
        $YOUR_DOMAIN = 'http://127.0.0.1:8000';

        foreach ($cart->getFull() as $product){
            $products_for_stripe[]= [ //permet d'afficher le recapitulatif de la commande avant payment
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product['product']->getPrice(),
                    'product_data' => [
                        'name' => $product['product']->getName(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$product['product']->getIllustration()],
                    ],
                 ],
                'quantity' => ($product['quantity'])

            ];

        }

        Stripe::setApiKey('sk_test_51LmJYgGAdoeJu0QQW0oEFr6z0ZdvrBog1uRU2a2d41xsJqBVnIFeUBpMoILIs3WB7PabrLDZAARDsmQ8SDvnBuyS00NrMKjSYz');

            

        $checkout_session = \Stripe\Checkout\Session::create([
        'line_items' => [
             
            $products_for_stripe
        
        ],

        
        'mode' => 'payment',
        'success_url' => $YOUR_DOMAIN . '/success.html',
        'cancel_url' => $YOUR_DOMAIN . '/cancel.html',

        
        ]);    

        $response = new JsonResponse(['id' => $checkout_session->id]);
        return $response;
    }
}
