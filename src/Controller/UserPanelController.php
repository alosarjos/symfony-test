<?php

namespace App\Controller;

use App\Entity\UrlEntry;
use App\Form\UrlEntryType;
use App\Helpers\UrlShorter\UrlShorterFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserPanelController extends AbstractController
{
    #[Route('/user/panel', name: 'user_panel')]
    public function index(Request $request, Security $security): Response
    {

        $currentUser = $security->getUser();

        $this->formSubmitAction($request, $currentUser);

        $urlEntry = new UrlEntry();
        $urlEntryForm = $this->createForm(UrlEntryType::class, $urlEntry);

        $urlEntries = $this->getDoctrine()
            ->getRepository(UrlEntry::class)
            ->findBy(['owner' => $currentUser]);

        return $this->render('user_panel/index.html.twig', [
            'controller_name' => 'UserPanelController',
            'form' => $urlEntryForm->createView(),
            'entries' => $urlEntries,
        ]);
    }


    private function formSubmitAction($request, $currentUser): void
    {
        $urlEntry = new UrlEntry();
        $urlEntryForm = $this->createForm(UrlEntryType::class, $urlEntry);
        $urlEntryForm->handleRequest($request);
        if ($urlEntryForm->isSubmitted() && $urlEntryForm->isValid()) {
            $realUrl = $urlEntry->getRealUrl();
            $shortAlgorythm = UrlShorterFactory::getShorter($urlEntryForm->get('algorythm')->getData());
            $this->upsertNewUrlEntry($realUrl, $shortAlgorythm, $currentUser);
        }
    }

    private function upsertNewUrlEntry($realUrl, $shortAlgorythm, $currentUser): void
    {
        do {
            $shortUrl = $shortAlgorythm->getShorted($realUrl);
            $urlEntry = $this->getDoctrine()
                ->getRepository(UrlEntry::class)
                ->findOneBy(['shortUrl' => $shortUrl]);
        } while ($urlEntry && $shortAlgorythm->canRetry());

        if ($urlEntry)
            throw new BadRequestException('Entry already exists with that shortUrl', 409);

        $urlEntry = new UrlEntry();
        $urlEntry->setRealUrl($realUrl);
        $urlEntry->setShortUrl($shortUrl);
        $urlEntry->setOwner($currentUser);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($urlEntry);
        $entityManager->flush();
    }

    #[Route('/user/panel/delete/{shortUrl}', name: 'delete_element')]
    public function deleteUrlEntry($shortUrl, Security $security): Response
    {
        $currentUser = $security->getUser();

        $urlEntry = $this->getDoctrine()
            ->getRepository(UrlEntry::class)
            ->findOneBy(['shortUrl' => $shortUrl]);

        if ($urlEntry->getOwner() !== $currentUser)
            throw new AccessDeniedException();

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($urlEntry);
        $entityManager->flush();

        return $this->redirectToRoute('user_panel');
    }
}
