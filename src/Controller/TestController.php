<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TestSession;
use App\Enum\State;
use App\Repository\QuestionRepository;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/test/{id}',
    name: 'test_form',
    methods: [Request::METHOD_GET],
)]
final class TestController extends AbstractController
{
    public function __construct(
        private readonly QuestionRepository $questionRepository,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function __invoke(TestSession $testSession): Response
    {
        try {
            if ($testSession->getState() === State::completed) {
                throw new NotFoundHttpException();
            }

            $questions = $this->questionRepository->findAll();
            shuffle($questions);

            return $this->render('test/index.html.twig', [
                'questions' => $questions,
                'testSession' => $testSession,
            ]);
        } catch (Exception $e) {
            $this->logger->error('Error creating test: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            return $this->render('error/error.html.twig');
        }
    }
}
