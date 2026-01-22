<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
    #[Route('/boutique', name: 'app_shop', methods: ['GET'])]
    public function index(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findBy([], ['id' => 'DESC']);

        return $this->render('shop/index.html.twig', [
            'products' => $products,
        ]);
    }

    #[Route('/produit/{id}', name: 'app_product_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('shop/show.html.twig', [
            'product' => $product,
        ]);
    }
}
