<?php

namespace App\Controller;

use App\Entity\UrlEntry;
use App\Entity\UrlStats;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ShortUrlRedirectionController extends AbstractController
{
    #[Route('redir/{shortUrl}', name: 'short_url_redirection')]
    public function index(Request $request, $shortUrl): Response
    {
        $urlEntry = $this->getDoctrine()
            ->getRepository(UrlEntry::class)
            ->findOneBy(['shortUrl' => $shortUrl]);

        if (!$urlEntry)
            throw $this->createNotFoundException('The requested short url does NOT exist');

        $this->updateEntryCounter($urlEntry, $request);

        return $this->redirect("https://" . $urlEntry->getRealUrl());
    }

    private function updateEntryCounter($urlEntry, $request): void
    {
        $userAgent = $request->headers->get('User-Agent');

        $urlStat = new UrlStats();
        $urlStat->setUrl($urlEntry);
        $urlStat->setDeviceType($userAgent);

        $now = new \DateTime();
        $urlStat->setAccessTimestamp($now->getTimestamp());

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($urlStat);
        $entityManager->flush();
    }
}
