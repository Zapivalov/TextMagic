<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Question;

final readonly class TestResultDto
{
    /**
     * @param list<string> $selectedAnswers
     */
    public function __construct(
        public Question $question,
        public array $selectedAnswers,
        public bool $correct,
    ) {
    }
}
