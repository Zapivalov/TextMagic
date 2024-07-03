<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnswerRepository::class)]
class Answer
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'text', type: 'string', nullable: false)]
    private string $text;

    #[ORM\Column(name: 'is_correct', type: 'boolean', nullable: false)]
    private bool $correct;

    #[ORM\ManyToOne(targetEntity: Question::class, inversedBy: 'answers')]
    #[ORM\JoinColumn(nullable: false)]
    private Question $question;

    public function __construct(
        string $text,
        bool $correct,
    ) {
        $this->text = $text;
        $this->correct = $correct;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function isCorrect(): bool
    {
        return $this->correct;
    }

    public function setQuestion(?Question $question): void
    {
        $this->question = $question;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }
}
