<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TestSession;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Clock\ClockInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/start',
    name: 'test_start',
    methods: [Request::METHOD_GET],
)]
final class TestStartController extends AbstractController
{
    public function __construct(
        private readonly ClockInterface $clock,
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(Request $request): Response
    {
        try {
            $testSession = new TestSession($this->clock->now());
            $this->entityManager->persist($testSession);
            $this->entityManager->flush();

            return $this->redirectToRoute('test_form', ['id' => $testSession->getId()]);
        } catch (Exception $e) {
            $this->logger->error('Session creation error: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return $this->render('error/error.html.twig');
        }
    }
}
