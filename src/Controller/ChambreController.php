<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Entity\Commande;
use App\Entity\DetailsCommande;
use App\Filter\ChambreFilter;
use App\Form\AvisType;
use App\Form\ChambreFilterType;
use App\Form\CommandeType;
use App\Repository\AvisRepository;
use App\Repository\ChambreRepository;
use App\Repository\CommandeRepository;
use App\Repository\DetailsCommandeRepository;
use App\Service\Panier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ChambreController extends AbstractController
{
    public function __construct(ChambreRepository $chambreRep)
    {
        $this->chambreRep = $chambreRep;
    }

    #[Route('/chambres', name: 'app_chambre')]
    public function index(): Response
    {
        $chambres = $this->chambreRep->findAll();

        return $this->render('chambre/index.html.twig', [
            'chambres' => $chambres,
        ]);
    }


    #[Route('/chambre/disponibilite', name: 'chambre_disponible')]
    public function disponible(Request $request, ChambreRepository $chambreRep, Panier $panier): Response
    {
        $filter = new ChambreFilter();
        $form = $this->createForm(ChambreFilterType::class, $filter);
        $form->handleRequest($request);

        $chambres = [];
        if ($form->isSubmitted() && $form->isValid()) {

            $chambres = $chambreRep->filter($filter);
            
            return $this->render('chambre/details.html.twig', [
                'chambres' => $chambres,
                'form_dispo' => $form->createView(),
            ]);
        }

        return $this->render('chambre/index.html.twig', [
            'chambres' => $chambres,
            'form_dispo' => $form->createView(),
        ]);
    }




    #[Route('/chambre/details/{id}', name: 'chambre_details')]
    public function voir( $id, Request $request, AvisRepository $avisRep, CommandeRepository $commandeRep, DetailsCommandeRepository $detailsRep , ChambreRepository $chambreRep): Response
    {
        
        $chambre = $this->chambreRep->find($id);
        
        $lesAvis = $avisRep->findBy(['chambre' => $chambre]);

        $avis = new Avis();
        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $user = $this->getUser();
            $avis->setEnregistreAt(new \DateTimeImmutable('now'));
            $avis->setUser($user);
            $avis->setChambre($chambre);

            $avisRep->add($avis, true);

            $this->addFlash('success' , 'Merci pour votre commentaire');
           return $this->redirectToRoute('chambre_details', ['id' => $chambre->getId()]);
        }


        // $commande = new Commande();
        // $filter = new ChambreFilter();
        // $form1 = $this->createForm(ChambreFilterType::class, $filter);
        // $form1->handleRequest($request);

        // //****************************************************************** */

        // if($form1->isSubmitted() && $form1->isValid()){
        //     $commande->setEnregistreAt(new \DateTimeImmutable('now'));
        //     $commande->setUser($this->getUser());
        //     $days = date_diff($filter->from , $filter->to);
        //     $commande->setPrix($chambre->getPrix() * $days->d);

        //     $commandeRep->add($commande, true);

        //     $detail = new DetailsCommande();
        //     $detail->setCommande($commande);
        //     $detail->setChambre($chambre);
        //     $detail->setStartAt($filter->from);
        //     $detail->setEndAt($filter->to);
        //     $detail->setPrix($chambre->getPrix());

        //     $detailsRep->add($detail, true);

        //     $this->addFlash('reserve-success', 'Merci pour votre réservation !');

        //     return $this->redirectToRoute('chambre_details', ['id' => $chambre->getId()]);

        // }

        //**************************************************************************** */
        $filtre2 = new ChambreFilter();
        $form2 = $this->createForm(ChambreFilterType::class, $filtre2);
        $form2->handleRequest($request);

        if($form2->isSubmitted() && $form2->isValid()){
            $ch = $chambreRep->filter($filtre2);
            if(in_array($chambre , $ch , true)){
                $this->addFlash('reserve-success', 'Merci pour votre réservation !');
            }else{
                $this->addFlash('reserve-success', 'Merci pour votre réservation !');
            }
        }


        //******************************************************************************* */

        return $this->render('chambre/details.html.twig', [
            'chambre' => $chambre,
            'form' => $form->createView(),
            'lesAvis' => $lesAvis,
            //'form_reservation' => $form1->createView(),
            'form_dispo' => $form2->createView(),
        ]);
    }



    #[Route('/chambre/reserver/{id}', name: 'chambre_reserver')]
    public function reserver($id, ChambreRepository $chambreRep, Request $request): Response
    {
        $chambre = $chambreRep->find($id);

        $commande = new Commande();
        $form1 = $this->createForm(CommandeType::class, $commande);
        $form1->handleRequest($request);

        if($form1->isSubmitted() && $form1->isValid()){

        }


        return $this->render('chambre/details.html.twig', [
            'form_reservation' => $form1->createView(),
        ]);
    }


}
