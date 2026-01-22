<?php

namespace App\Controller;

use App\Entity\Product;
use Stripe\Checkout\Session;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class CheckoutController extends AbstractController
{
    #[Route('/checkout/{id}', name: 'app_checkout', methods: ['POST'])]
    public function checkout(Product $product, Request $request): RedirectResponse
    {
        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);

        $session = Session::create([
            'mode' => 'payment',
            'line_items' => [[
                'quantity' => 1,
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(), // centimes
                    'product_data' => [
                        'name' => $product->getName(),
                    ],
                ],
            ]],
            'success_url' => $request->getSchemeAndHttpHost()
                .$this->generateUrl('app_checkout_success')
                .'?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => $request->getSchemeAndHttpHost()
                .$this->generateUrl('app_checkout_cancel'),
        ]);

        return $this->redirect($session->url, 303);
    }

    #[Route('/checkout/success', name: 'app_checkout_success', methods: ['GET'])]
    public function success(): Response
    {
        return $this->render('checkout/success.html.twig');
    }

    #[Route('/checkout/cancel', name: 'app_checkout_cancel', methods: ['GET'])]
    public function cancel(): Response
    {
        return $this->render('checkout/cancel.html.twig');
    }
}
