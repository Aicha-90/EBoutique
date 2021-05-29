<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface as EMI;
use App\Entity\Produit;
use App\Form\AjouterProduitType;
use App\Repository\ProduitRepository;

class ProduitController extends AbstractController
{
    /**
     * @Route("/produit/{categorie}", name="produit")
     */
    public function index(ProduitRepository $pr,string $categorie): Response
    {
        if($categorie === "homme"){
            $produits=$pr->findBy( array('categorie' => 'homme'));
        }
        if($categorie === "femme"){
            $produits=$pr->findBy( array('categorie' => 'femme'));
        }
        if($categorie === "enfant"){
            $produits=$pr->findBy( array('categorie' => 'enfant'));
        }
        return $this->render('produit/index.html.twig', [
            'listeProduit' => $produits
        ]);
    }

    /**
     * @Route("/add", name="produit_add")
     */
    public function addProduit(Request $rq, EMI $em): Response
    {

        $produit= new Produit();
        $form = $this->createForm(AjouterProduitType::class, $produit);
        $form->handleRequest($rq);

        if ($form->isSubmitted() && $form->isValid()) {

            // je récupère les informations de mon formulaire pour créer
            //une nouvelle entité Produit
            $newProduit = $form->getData();

            // je récupère la valeur du paramètre global "dossier_images"
            // pour définir dans quel dossier va
            // être enregistré l'image téléchargée
            $destination = $this->getParameter("dossier_images");

            // je mets les informations de la photo téléchargée dans la
            // variable $photoTelechargee et s'il y a bien une photo téléchargée
            if($photoTelechargee = $form["photo"]->getData()){

                // je récupère le nom de la photo dans $nomPhoto
                $nomPhoto = pathinfo($photoTelechargee->getClientOriginalName(), PATHINFO_FILENAME);
                
                //MANIPULATION DU NOM DE LA PHOTO TELECHARGEE
                // je supprime les éventuels espace (au début et à la fin) du string $nomPhoto
                $nouveauNom = trim($nomPhoto);
                // je remplace les espaces par _ dans $nouveauNom (il vaut mieux éviter les espaces dans les noms de fichier)
                $nouveauNom = str_replace(" ", "_", $nouveauNom);
                // je concatène une chaîne de caractères unique pour éviter d'avoir 2 photos avec le même nom
                // (sinon, la photo précédente sera écrasée)
                $nouveauNom .= "-" . uniqid() . "." . $photoTelechargee->guessExtension();

                // je deplace la photo dans le repertoir de destination
                $photoTelechargee->move($destination, $nouveauNom);

                $newProduit->setPhoto($nouveauNom);
                $newProduit->setTaille(0);

                // j'insere dans la bdd

                $em->persist($newProduit);
                $em->flush();

                // je définie le message qui sera affiché
                $this->addFlash("success", "Votre annonce a bien été enregistrée");
                // je redirige vers une route
                return $this->redirectToRoute("user_mon_compte_admin");
            }

            else{
                $this->addFlash("error", "Il manque des informations pour enregistrer votre annonce");
            }
        }

        return $this->render('produit/formAjouter.html.twig', [
            'AjouterProduitForm' => $form->createView(),
        ]);
    }

     /**
     * @Route("/gestion_produit", name="produit_gestion")
     */
    public function gestion(ProduitRepository $rq): Response
    {
        $listeProduit=$rq->findAll();

        return $this->render('produit/gestion_produit.html.twig',['listeProduit' => $listeProduit]);
    }

     /**
     * @Route("/produit_supprimer/{id}", name="produit_supprimer")
     */
    public function supprimer(ProduitRepository $rp, int $id, EMI $em): Response
    {
        $produitAsupprimer=$rp->find($id);
        
        $em->remove($produitAsupprimer);
        $em->flush();
            
        return $this->redirectToRoute("produit_gestion");
    }

     /**
     * @Route("/produit_selection/{id}", name="produit_selection")
     */
    public function produitSelect(ProduitRepository $rp, int $id, EMI $em): Response
    {
        return $this->render('produit/modifier_produit.html.twig',['prodAmodif' => $rp->find($id)]);
    }

    /**
     * @Route("/produit_modifier/{id}", name="produit_modifier")
     * 
     */
    public function modifier(ProduitRepository $rp, Request $request, EMI $em, int $id)
    {
        $produitAmodifier = $rp->find($id);

        if($request->isMethod("POST")){ 
            $titre = $request->request->get('titre');
            $categorie = $request->request->get('categorie');
            $description = $request->request->get('description');
            $couleur = $request->request->get('couleur');
            $prix = $request->request->get('prix');
            $stock = $request->request->get('stock');
            $reference = $request->request->get('reference');
            $photo=$request->request->get('photo');
            
            $produitAmodifier->setTitre($titre);
            $produitAmodifier->setCategorie($categorie);
            $produitAmodifier->setDescription($description);
            $produitAmodifier->setCouleur($couleur);
            $produitAmodifier->setPrix($prix);
            $produitAmodifier->setStock($stock);
            $produitAmodifier->setReference($reference);
            $produitAmodifier->setPhoto($photo);
            
            $em->persist($produitAmodifier);
            $em->flush();

            return $this->redirectToRoute("produit_gestion");

        }
        else{
            return $this->render('produit/modifier_produit.html.twig',['prodAmodif' => $rp->find($id)]);
        }
    }


}
