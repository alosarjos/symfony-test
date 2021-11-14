<?php

namespace App\Controller;

use App\Entity\UrlEntry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/api/stats/{shortUrl}', name: 'api_single_stats')]
    public function get($shortUrl): Response
    {
        $urlEntry = $this->getDoctrine()
            ->getRepository(UrlEntry::class)
            ->findOneBy(['shortUrl' => $shortUrl]);

        if (!$urlEntry)
            throw $this->createNotFoundException('The requested short url does NOT exist');

        return new JsonResponse($urlEntry->toJson());
    }


    #[Route('/api/stats', name: 'api_stats')]
    public function getAll(): Response
    {
        $urlEntries = $this->getDoctrine()
            ->getRepository(UrlEntry::class)
            ->findAll();

        $jsonData = array_map(function ($entry) {
            return $entry->toJson();
        }, $urlEntries);

        return new JsonResponse($jsonData);
    }
}
