<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TestResultRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestResultRepository::class)]
class TestResult
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\ManyToOne(targetEntity: TestSession::class, inversedBy: 'testResults')]
    #[ORM\JoinColumn(nullable: false)]
    private TestSession $testSession;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    #[ORM\JoinColumn(name: 'question_id', referencedColumnName: 'id')]
    private Question $question;

    #[ORM\JoinTable(name: 'test_results_answers')]
    #[ORM\JoinColumn(name: 'test_result_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'answer_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: Answer::class, inversedBy: 'testResults')]
    private Collection $answers;

    #[ORM\Column(name: 'correct', type: 'boolean', nullable: false)]
    private bool $correct;

    public function __construct(
        TestSession $testSession,
        Question $question,
        array $answers,
        bool $correct,
    ) {
        $this->testSession = $testSession;
        $this->question = $question;
        $this->answers = new ArrayCollection($answers);
        $this->correct = $correct;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isCorrect(): bool
    {
        return $this->correct;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function getTestSession(): TestSession
    {
        return $this->testSession;
    }
}
