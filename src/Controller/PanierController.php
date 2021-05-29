<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Produit;
use App\Services\Cart\CartService;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     */
    public function index(CartService $cartService): Response
    {

        return $this->render('panier/index.html.twig', [
        'items' => $cartService->getFullCart(), 'total' => $cartService->getTotal()
        ]);
    }

    /**
     * @Route("/panier/ajouter/{id}", name="panier_ajouter")
     */
    public function ajouterPanier($id, CartService $cartService){

        $cartService->ajouterPanier($id);
        return $this->redirectToRoute("panier");
        
    }

    /**
     * @Route("/panier/supp/{id}" , name="panier_supp" )
     */
    public function suppPanier($id, CartService $cartService){

        $cartService->suppPanier($id);
 
         return $this->redirectToRoute("panier");
    }

        /**
     * @Route("/panier/retirer/{id}", name="panier_retirer")
     */
    public function retirerPanier($id, CartService $cartService, Request $rq){

        $cartService->retirerPanier($id);
        return $this->redirectToRoute("panier");

    }

    /*
    // Methode avec Request
    public function ajouterPanier($id, Request $rq){

        // J'accede à la session par la requete(HttpFoudation)
        $session=$rq->getSession();

        // J'accede au "panier" grace à la session, 
        // s'il n'y a pas de panier alors je crée un tableau associatif qui sera mon "panier"
        $panier=$session->get("panier",[]);

        // J'ajoute un produit dans mon panier

        // Si j'ai dejà un produit avec cet identifiant alors j'ajoute
        // Sinon j'en met un
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
           $panier[$id]=1; 
        }
        

        // Je met à jour mon panier
        $session->set("panier",$panier);

        dd($session->get("panier"));

    }
    */

 


}
