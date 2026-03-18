<?php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ShopController extends AbstractController
{
    #[Route('/boutique', name: 'app_shop', methods: ['GET'])]
    public function index(EntityManagerInterface $em, Request $request): Response
    {
        // Récupère toutes les catégories pour afficher les sections et le filtre
        $categories = $em->getRepository(Category::class)->findAll();

        // Récupère l'id de catégorie passé en paramètre GET (?category=1)
        // getInt() retourne 0 si le paramètre est absent ou invalide
        $categoryId = $request->query->getInt('category');

        // Initialise la catégorie active à null (= pas de filtre = tout afficher)
        $currentCategory = null;

        // Si un id de catégorie est présent dans l'URL, on cherche la catégorie correspondante
        if ($categoryId) {
            $currentCategory = $em->getRepository(Category::class)->find($categoryId);
            // Si l'id n'existe pas en base, $currentCategory reste null (pas d'erreur)
        }

        // Récupère les produits qui ne sont associés à aucune catégorie
        // pour les afficher dans une section "Autres œuvres"
        $productsWithoutCategory = $em->getRepository(Product::class)->findBy(
            ['category' => null], // critère : pas de catégorie
            ['id' => 'DESC']      // tri : plus récent en premier
        );

        // Envoie les données au template Twig
        return $this->render('shop/index.html.twig', [
            'categories' => $categories,                   // toutes les catégories
            'currentCategory' => $currentCategory,         // catégorie filtrée (ou null)
            'productsWithoutCategory' => $productsWithoutCategory, // produits sans catégorie
        ]);
    }

    // Route pour afficher le détail d'un produit
    // {id} doit être un entier grâce au requirement '\d+'
    // Symfony injecte automatiquement le Product correspondant à l'id (ParamConverter)
    #[Route('/produit/{id}', name: 'app_product_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Product $product): Response
    {
        return $this->render('shop/show.html.twig', [
            'product' => $product,
        ]);
    }
}