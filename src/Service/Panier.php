<?php


namespace App\Service;

use App\Entity\Commande;
use App\Entity\DetailsCommande;
use App\Repository\ChambreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;



class Panier
{

    private $requestStack;
    private $chambreRep;
    private $manager;

    public function __construct(RequestStack $requestStack, ChambreRepository $chambreRep, EntityManagerInterface $manager)
    {
        $this->requestStack = $requestStack;
        $this->chambreRep = $chambreRep;
        $this->manager = $manager;
    }

    public function creation()
    {
        $panier = [
            'id' => [],
            'titre' => [],
            'prix' => [],
            'from' => [],
            'to' => [],
            'image' => []
        ];
        return $panier;
    }


    public function add($id, $titre,  $prix, $from, $to, $image)
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier');

        if (empty($panier)) {
            $panier = $this->creation();
            $session->set('panier', $panier);
        }

        $panier['id'][] = $id;
        $panier['titre'][] = $titre;
        $panier['prix'][] = $prix;
        $panier['from'][] = $from;
        $panier['to'][] = $to;
        $panier['image'][] = $image;

        $session->set('panier', $panier);
    }


    public function vider()
    {
        $session = $this->requestStack->getSession();
        $panier = $this->creation();
        $session->set('panier', $panier);
    }

    public function remove($id)
    {
        $session = $this->requestStack->getSession();
        $panier = $session->get('panier');

        $position = array_search($id, $panier['id']);

        /*
            array_splice permett d'éffacer une portion (un ou des élemants) dans un tableau

            3 argumaents :
                1 - tableau
                2 - la position
                3 - le npmbre d'élemant à supprimer
        */

        array_splice($panier['id'], $position, 1);
        array_splice($panier['titre'], $position, 1);
        array_splice($panier['prix'], $position, 1);
        array_splice($panier['from'], $position, 1);
        array_splice($panier['to'], $position, 1);
        array_splice($panier['image'], $position, 1);

        // deuxiem manier de faire
        /* $tableau = ["id" , "titre" , "prix" , "quantity"];

        for($i = 0; $i < count($tableau); $i++){
            array_splice($panier[$tableau[$i]], $position, 1);
        } */

        $session->set('panier', $panier);
    }

    public function montant()
    {
        $session = $this->requestStack->getSession();
        $montant = 0;

        $panier = $session->get('panier');

        for ($i = 0; $i < count($panier['id']); $i++) {
            $days = date_diff($panier['from'][$i], $panier['to'][$i]);
            $montant += $panier['prix'][$i] * $days->d;
        }

        $montant = round($montant, 2);

        return $montant;
    }


    public function paiement($user)
    {
        $session = $this->requestStack->getSession();
        //$this->verification();

        $panier = $session->get('panier');

        $size = count($panier['id']);


        $commande = new Commande();
        $commande->setUser($user);
        $commande->setEnregistreAt(new \DateTimeImmutable('now'));
        $commande->setPrix($this->montant());


        $this->manager->persist($commande);
        $this->manager->flush();

        for ($i = 0; $i < $size; $i++) {
            
                $chambre = $this->chambreRep->find($panier['id'][$i]);
                $detail = new DetailsCommande();
                $detail->setCommande($commande);
                $detail->setChambre($chambre);
                $detail->setStartAt($panier['from'][$i]);
                $detail->setEndAt($panier['to'][$i]);
                $detail->setPrix($chambre->getPrix());

                $this->manager->persist($detail);
                $this->manager->flush();

                $this->remove($panier['id'][$i]);
           
        }
    }
}
