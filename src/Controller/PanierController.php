<?php

namespace App\Controller;

use App\Service\Panier;
use App\Repository\ChambreRepository;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, Panier $panier): Response
    {
        if($session->get('panier')){
            //$panier->verification();
            $panierSession = $session->get('panier');
            $montant = $panier->montant();

            return $this->render('panier/index.html.twig', [
                'panier' => $panierSession,
                'montant' => $montant
    
            ]);
        }else{
            return $this->render('panier/index.html.twig');

        }   
    }


    #[Route('/ajouter', name: 'panier_ajouter')]
    public function panier_ajouter(Request $request, ChambreRepository $chambreRep, Panier $panier)
    {
        //Dans la class Request, se trouve superglobal
        //la propriété request consern $_POST
        //$request->request = $_POST
        //pour accéder à des positions de ce tableau, on utilise la méthode ->get() dans laquelle on y met le nom de la positions

        $idChambre = $request->request->get('chambre');
        $from = new DateTime($request->request->get('from'));
        $to = new DateTime($request->request->get('to'));
        
        

        $chambre = $chambreRep->find($idChambre);

        // dd($produit);

        $panier->add($idChambre, $chambre->getTitre(), $chambre->getPrix(), $from , $to  , $chambre->getImage());

        return $this->redirectToRoute('app_panier');
    }


    #[Route('/retirer/{id}', name: 'panier_retirer')]
    public function panier_retirer($id, Panier $panier)
    {
        $panier->remove($id);

        return $this->redirectToRoute('app_panier');
    }


    #[Route('/vider', name: 'panier_vider')]
    public function panier_vider(Panier $panier)
    {
        $panier->vider();

        return $this->redirectToRoute('app_panier');
    }


    #[Route('/payer', name: 'panier_payer')]
    public function panier_payer(Panier $panier)
    {

        $user = $this->getUser();


        $panier->paiement($user);
        $this->addFlash("success", "Merci, votre réservation sera traiter dans les plus brefs délais");

        return $this->redirectToRoute('app_panier');
    }



}
