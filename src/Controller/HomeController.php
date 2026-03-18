<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(EntityManagerInterface $em): Response
    {
        // Toutes les catégories pour la section univers
        $categories = $em->getRepository(Category::class)->findAll();

        // Les 4 produits les plus récents pour la section "Dernières créations"
        $latestProducts = $em->getRepository(Product::class)->findBy(
            [],
            ['id' => 'DESC'],
            4
        );

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'latestProducts' => $latestProducts,
        ]);
    }
}