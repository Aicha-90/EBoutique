<?php

namespace App\Services\Cart;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\ProduitRepository;

class CartService{

    protected $session;
    protected $pr;

    public function __construct(SessionInterface $session, ProduitRepository $pr){

        $this->session=$session;
        $this->pr=$pr;

    }

    public function ajouterPanier( int $id ){
        // J'accede au "panier" grace à la session, 
        // s'il n'y a pas de panier alors je crée un tableau associatif qui sera mon "panier"
        $panier=$this->session->get("panier",[]);

        // J'ajoute un produit dans mon panier

        // Si j'ai dejà un produit avec cet identifiant alors j'ajoute
        // Sinon j'en met un
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
           $panier[$id]=1; 
        }
        
        // Je met à jour mon panier
        $this->session->set("panier",$panier);
    }

    public function retirerPanier( int $id ){

        $panier=$this->session->get("panier",[]);

        if(!empty($panier[$id])){
            $panier[$id]--;
        }else{
           $panier[$id]=1; 
        }

        $this->session->set("panier",$panier);
    }

    public function suppPanier( int $id){

        $panier=$this->session->get("panier",[]);

        if(!empty($panier[$id]) || $panier[$id] == 0 ){
            unset($panier[$id]);
        }

        $this->session->set('panier',$panier);
    }

    public function getFullCart() : array {
        // Içi je veux récuperer les donnees du panier
        // Pour cela j'utilise SessionInterface $session
        $panier=$this->session->get('panier',[]);

        // Traduire les données contenue dans la session en tableau
        $panierDonnees=[];

        foreach( $panier as $id => $quantite){
            $panierDonnees[]=[
                'produit'=> $this->pr->find($id),
                'quantite'=> $quantite
            ];
        }

        return $panierDonnees;
    }

    public function getTotal() : float {

        $total=0;

        foreach( $this->getFullCart() as $item){
            $total+=$item['produit']->getPrix( )* $item['quantite'];
        }

        return $total;
    }


}