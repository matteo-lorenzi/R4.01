<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\BoutiqueService;

class BoutiqueController extends AbstractController
{
    #[Route('/{_locale}/boutique', name: 'app_boutique')]
    public function index(BoutiqueService $boutique): Response
    {
        $categories = $boutique->findAllCategories();
        return $this->render('boutique/index.html.twig', [
            'controller_name' => 'BoutiqueController', 'categories' => $categories
        ]);
    }

    #[Route('/{_locale}/rayon/{idCategorie}', name: 'app_boutique_rayon')]
    public function rayon(int $idCategorie, BoutiqueService $boutiqueService): Response
    {
        $categorie = $boutiqueService->findCategorieById($idCategorie);

        if ($categorie === null) {
            throw $this->createNotFoundException('Catégorie non trouvée');
        }

        $produits = $boutiqueService->findProduitsByCategorie($idCategorie);


        return $this->render('boutique/rayon.html.twig', [
            'categorie' => $categorie,
            'produits' => $produits,
        ]);
    }
}
