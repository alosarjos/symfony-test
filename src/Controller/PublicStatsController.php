<?php

namespace App\Controller;

use App\Entity\UrlEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicStatsController extends AbstractController
{
    #[Route('/public/stats', name: 'public_stats')]
    public function index(): Response
    {
        $urlEntries = $this->getDoctrine()
            ->getRepository(UrlEntry::class)
            ->findAll();

        return $this->render('public_stats/index.html.twig', [
            'controller_name' => 'PublicStatsController',
            'entries' => $urlEntries,
        ]);
    }
}
