<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Security\LoginFormAuthenticator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Form\UserSubsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Doctrine\ORM\EntityManagerInterface as EMI;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    /**
    * @Route("/inscription", name="user_subs")
    * 
    */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $authenticator): Response
    {
       

        $user = new User();
        $form = $this->createForm(UserSubsType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //Chaque utilisateur crée est un user
            $user->setRoles($user->getRoles());

            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $this->redirectToRoute("accueil");
        }

        return $this->render('user/index.html.twig', [
            'UserSubs' => $form->createView(),
        
        ]);
    }

    /**
    * @Route("/mon_compte", name="user_mon_compte")
    *  
    */
    public function monCompteUser(){

        return $this->render('user/mon_compte.html.twig');
    }

    /**
    * @Route("/gestion_membre", name="user_gestion")
    * @Security("is_granted('ROLE_ADMIN')")
    */
    public function gestionMembre(UserRepository $ur){

        return $this->render('admin/gestion_membre.html.twig', [
            'listUser' => $ur->findAll()]);
    }

    /**
    * @Route("/user_supp/{id}", name="user_supp")
    * 
    */
    public function suppMembre(int $id, EMI $em, UserRepository $ur){

        $userAsupprimer=$ur->find($id);
            
        $em->remove($userAsupprimer);
        $em->flush();

        //return $this->redirectToRoute('accueil');
        return $this->render('user/user_supprimer.html.twig', ["user" => $userAsupprimer]);
    }

    /**
     * @Route("/user_modifier/{id}", name="user_modifier")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function modifier(UserRepository $rp, Request $request, EMI $em, int $id)
    {
        $userAmodifier = $rp->find($id);

        if($request->isMethod("POST")){ 
            $pseudo = $request->request->get('pseudo');
            $nom = $request->request->get('nom');
            $prenom = $request->request->get('prenom');
            $role = $request->request->get('role');
            $email = $request->request->get('email');
            $sexe = $request->request->get('sexe');
            $adresse = $request->request->get('adresse');
            $cp=$request->request->get('cp');
            $ville=$request->get('ville');
            
            $userAmodifier->setPseudo($pseudo);
            $userAmodifier->setNom($nom);
            $userAmodifier->setPrenom($prenom);
            $userAmodifier->setSexe($rp->find($id)->getSexe());
            $roles=[$role,""];
            $userAmodifier->setRoles($roles);
            $userAmodifier->setEmail($email);
            $userAmodifier->setPassword($rp->find($id)->getPassword());
            $userAmodifier->setAdresse($adresse);
            $userAmodifier->setCp($cp);
            $userAmodifier->setVille($ville);
            
            $em->persist($userAmodifier);
            $em->flush();

            return $this->redirectToRoute("user_gestion");

        }
        else{
            return $this->render('admin/user_modifier.html.twig',['userAmodif' => $rp->find($id)]);
        }
    }

    /**
     * @Route("/user_modifier_infos/{id}", name="user_modifier_infos")
     * 
     */
    public function modifierInfos(UserRepository $rp, Request $request, EMI $em, int $id)
    {
        $userAmodifier = $rp->find($id);

        if( $request->isMethod("POST") ){ 
            $pseudo = $request->request->get('psdo');
            $nom = $request->request->get('lastName');
            $prenom = $request->request->get('firstName');
            $email = $request->request->get('mail');
            $sexe = $request->request->get('sxe');
            $adresse = $request->request->get('ad');
            $cp=$request->request->get('codep');
            $ville=$request->get('city');
            
            $userAmodifier->setPseudo($pseudo);
            $userAmodifier->setNom($nom);
            $userAmodifier->setPrenom($prenom);
            $userAmodifier->setSexe($rp->find($id)->getSexe());
            $userAmodifier->setEmail($email);
            $userAmodifier->setPassword($rp->find($id)->getPassword());
            $userAmodifier->setAdresse($adresse);
            $userAmodifier->setCp($cp);
            $userAmodifier->setVille($ville);
            
            $em->persist($userAmodifier);
            $em->flush();

            return $this->redirectToRoute("user_mon_compte");

        }
        
        return $this->render('user/user_modifier.html.twig',[ 'mesInfos' => $userAmodifier]);
        
    }


}
