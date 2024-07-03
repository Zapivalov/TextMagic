<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\State;
use App\Repository\TestSessionRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TestSessionRepository::class)]
class TestSession
{
    #[ORM\Column(name: 'id', type: 'integer', nullable: false)]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    private int $id;

    #[ORM\Column(name: 'created_at', type: 'datetime_immutable', nullable: false)]
    private DateTimeImmutable $createdAt;

    #[ORM\OneToMany(
        targetEntity: TestResult::class,
        mappedBy: 'testSession',
        cascade: ['persist', 'remove'],
        orphanRemoval: true
    )]
    private Collection $testResults;

    #[ORM\Column(name: 'state', nullable: false, enumType: State::class)]
    private State $state;

    public function __construct(
        DateTimeImmutable $createdAt,
    ) {
        $this->createdAt = $createdAt;
        $this->state = State::inProgress;
        $this->testResults = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTestResults(): Collection
    {
        return $this->testResults;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getState(): State
    {
        return $this->state;
    }

    /**
     * @param list<TestResult> $testResults
     */
    public function complete(array $testResults): void
    {
        if ($this->state === State::completed) {
            throw new \RuntimeException('Can not complete completed test');
        }

        $this->testResults = new ArrayCollection($testResults);
        $this->state = State::completed;
    }
}
