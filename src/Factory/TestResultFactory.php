<?php

declare(strict_types=1);

namespace App\Factory;

use App\Dto\TestResultDto;
use App\Entity\TestResult;
use App\Entity\TestSession;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;

final readonly class TestResultFactory
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private AnswerRepository $answerRepository,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @param list<TestResultDto> $results
     */
    public function create(TestSession $testSession, array $results): void
    {
        try {
            $allAnswers = $this->answerRepository->findAll();

            $answersById = [];
            foreach ($allAnswers as $answer) {
                $answersById[$answer->getId()] = $answer;
            }

            $testResults = [];

            foreach ($results as $result) {
                $selectedAnswerIds = $result->selectedAnswers;

                $selectedAnswers = array_filter($answersById, function($answer) use ($selectedAnswerIds) {
                    return in_array($answer->getId(), $selectedAnswerIds);
                });

                $testResult = new TestResult(
                    $testSession,
                    $result->question,
                    $selectedAnswers,
                    $result->correct,
                );

                $this->entityManager->persist($testResult);
                $testResults[] = $testResult;
            }

            $testSession->complete($testResults);

            $this->entityManager->flush();
        } catch (Exception $e) {
            $this->logger->error('Error creating test results: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            throw new RuntimeException('An error occurred while generating test results.', 0, $e);
        }
    }
}
