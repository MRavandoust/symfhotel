<?php

namespace App\Controller;

use App\Repository\ChambreRepository;
use App\Service\Panier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function index(SessionInterface $session, Panier $panier): Response
    {
        if($session->get('panier')){
            $panier->verification();
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
        $from = "";
        $to = "";
        

        $chambre = $chambreRep->find($idChambre);

        // dd($produit);

        $panier->add($idChambre, $chambre->getTitre(), $chambre->getPrix(), $from , $to  , $chambre->getImage());

        return $this->redirectToRoute('app_panier');
    }
}
