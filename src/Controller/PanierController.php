<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\PanierService;
use App\Service\BoutiqueService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PanierController extends AbstractController
{
    #[Route('/{_locale}/panier/', name: 'app_panier_index', requirements: ['_locale' => 'fr|en|jp|it'])]
    public function index(PanierService $panierService): Response
    {
        $contenuPanier = $panierService->getContenu();
        $totalPanier = $panierService->getTotal();

        return $this->render('panier/index.html.twig', [
            'panier' => $contenuPanier,
            'total' => $totalPanier,
        ]);
    }

    #[Route('/{_locale}/panier/ajouter/{idProduit}/{quantite}', name: 'app_panier_ajouter', requirements: ['_locale' => 'fr|en|jp|it', 'idProduit' => '\d+', 'quantite' => '\d+'])]
    public function ajouter(int $idProduit, int $quantite, Request $request, PanierService $panierService, BoutiqueService $boutiqueService): Response
    {
        if (!$boutiqueService->findProduitById($idProduit)) {
            throw $this->createNotFoundException('Le produit demandé n\'existe pas.');
        }

        $panierService->ajouterProduit($idProduit, $quantite);

        return $this->redirectToRoute('app_panier_index', ['_locale' => $request->getLocale()]);
    }

    #[Route('/{_locale}/panier/enlever/{idProduit}/{quantite}', name: 'app_panier_enlever', requirements: ['_locale' => 'fr|en|jp|it', 'idProduit' => '\d+', 'quantite' => '\d+'])]
    public function enlever(int $idProduit, int $quantite, Request $request, PanierService $panierService): Response
    {
        $panierService->enleverProduit($idProduit, $quantite);

        return $this->redirectToRoute('app_panier_index', ['_locale' => $request->getLocale()]);
    }

    #[Route('/{_locale}/panier/supprimer/{idProduit}', name: 'app_panier_supprimer', requirements: ['_locale' => 'fr|en|jp|it', 'idProduit' => '\d+'])]
    public function supprimer(int $idProduit, Request $request, PanierService $panierService): Response
    {
        $panierService->supprimerProduit($idProduit);

        return $this->redirectToRoute('app_panier_index', ['_locale' => $request->getLocale()]);
    }

    #[Route('/{_locale}/panier/vider', name: 'app_panier_vider', requirements: ['_locale' => 'fr|en|jp|it'])]
    public function vider(Request $request, PanierService $panierService): Response
    {
        $panierService->vider();

        return $this->redirectToRoute('app_panier_index', ['_locale' => $request->getLocale()]);
    }

    public function nombreProduits(PanierService $panier): Response
    {
        $nombreProduits = $panier->getNombreProduits();
        $response = new Response($nombreProduits);
        return $response;
    }
}
