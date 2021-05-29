<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as EMI;
use App\Repository\UserRepository;
use App\Entity\Commande;
use App\Services\Cart\CartService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\CommandeRepository;


class CommandeController extends AbstractController
{
    /**
     * @Route("/commande", name="commande")
     */
    public function index(): Response
    {
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }

    /**
     * @Route("/commande/gestion", name="commande_gestion")
     */
    public function gestionCommande(CommandeRepository $cmd): Response
    {
        $allCmd=$cmd->findAll();

        return $this->render('commande/gestion_commande.html.twig', [
            'listeAllCmd' => $allCmd,
        ]);
    }

    /**
     * @Route("/commande/{id}", name="commande_user")
     */
    
    public function mesCommande($id,CommandeRepository $cmd ): Response
    {
        $mesCmd= $cmd->findBy( array('user' => $id));

        return $this->render('user/gestion_commande.html.twig', [
            'listeCmd' => $mesCmd,
        ]);
    }

    /**
     * @Route("/commande/{id}/{montant}", name="commande_paiement")
     */
    public function paiement(int $id, $montant, SessionInterface $session, UserRepository $ur, EMI $em): Response
    {
        $commande= new Commande();
        $user=$ur->find($id);
        $commande->setUser($user);
        $commande->setMontant($montant);
        $commande->setEtat("Livré");
        $commande->setDate(new \DateTime());

        $em->persist($commande);
        $em->flush();
        /*
        $panier=$cartService->getFullCart();
        dd($panier);
        foreach( $panier as $i => $item){
            unset($item);
        }*/
        $session->clear();
        return $this->render('commande/index.html.twig', [
            'controller_name' => 'CommandeController',
        ]);
    }


}
