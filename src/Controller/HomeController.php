<?php

namespace App\Controller;

use App\Filter\ChambreFilter;
use App\Form\ChambreFilterType;
use App\Repository\ChambreRepository;
use App\Repository\SliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(SliderRepository $sliderRep, Request $request, ChambreRepository $chambreRep): Response
    {
        $slides = $sliderRep->findAll();

        $filter = new ChambreFilter();
        $form = $this->createForm(ChambreFilterType::class, $filter);
        $form->handleRequest($request);

        $chambres = [];
        if ($form->isSubmitted() && $form->isValid()) {
            $chambres = $chambreRep->filter($filter);
            
            return $this->render('home/search.html.twig', [
                'chambres' => $chambres,
                'form' => $form->createView(),
            ]);
        }
       
        return $this->render('home/index.html.twig', [
            'slides' => $slides,
            'chambres' => $chambres,
            'form' => $form->createView(),
        ]);
    }

}
