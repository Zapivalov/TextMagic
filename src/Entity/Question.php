<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'text', type: 'string', nullable: false)]
    private string $text;

    #[ORM\OneToMany(
        targetEntity: Answer::class,
        mappedBy: 'question',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $answers;

    public function __construct(string $text)
    {
        $this->text = $text;
        $this->answers = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function correctCombinations(): array
    {
        $criteria = Criteria::create()
            ->where(Criteria::expr()->eq('correct', true));

        $correctAnswers = $this->answers->matching($criteria);

        return $correctAnswers->map(function($answer) {
            return (string)$answer->getId();
        })->toArray();
    }

    public function addAnswer(Answer $answer): void
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }
    }

    public function getShuffledAnswers(): ArrayCollection
    {
        $answers = $this->answers->toArray();
        shuffle($answers);

        return new ArrayCollection($answers);
    }
}
