<?php

declare(strict_types=1);

namespace App\Service;

use App\Dto\TestResultDto;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use RuntimeException;

final readonly class TestResultProcessor
{
    public function __construct(
        private QuestionRepository $questionRepository,
        private LoggerInterface $logger,
    ) {
    }

    public function processTestResults(array $postData): array
    {
        try {
            $answers = $postData['answers'];

            if (!isset($answers) || !is_array($answers)) {
                throw new InvalidArgumentException(
                    'Invalid data format: answers array is missing or not an array.'
                );
            }

            $questions = $this->questionRepository->findAll();
            $results = [];

            foreach ($questions as $question) {
                $selectedAnswers = $answers[$question->getId()] ?? [];

                $isCorrect = $selectedAnswers && $this->isCorrectAnswers($question, $selectedAnswers);

                $results[] = new TestResultDto(
                    $question,
                    $selectedAnswers,
                    $isCorrect,
                );
            }

            return $results;
        } catch (Exception $e) {
            $this->logger->error('Error processing test results: ' . $e->getMessage(), [
                'exception' => $e,
            ]);

            throw new RuntimeException('An error occurred while processing test results.', 0, $e);
        }
    }

    private function isCorrectAnswers(Question $question, array $selectedAnswers): bool
    {
        $intersection = array_intersect($selectedAnswers, $question->correctCombinations());

        return count($intersection) === count($selectedAnswers);
    }
}
