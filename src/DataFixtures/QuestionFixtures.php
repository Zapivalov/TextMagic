<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Answer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuestionFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $questionsData = [
            '1 + 1 = ?' => [
                ['text' => '3', 'isCorrect' => false],
                ['text' => '2', 'isCorrect' => true],
                ['text' => '0', 'isCorrect' => false],
            ],
            '2 + 2 = ?' => [
                ['text' => '4', 'isCorrect' => true],
                ['text' => '3 + 1', 'isCorrect' => true],
                ['text' => '10', 'isCorrect' => false],
            ],
            '3 + 3 = ?' => [
                ['text' => '1 + 5', 'isCorrect' => true],
                ['text' => '1', 'isCorrect' => false],
                ['text' => '6', 'isCorrect' => true],
                ['text' => '2 + 4', 'isCorrect' => true],
            ],
            '4 + 4 = ?' => [
                ['text' => '8', 'isCorrect' => true],
                ['text' => '4', 'isCorrect' => false],
                ['text' => '0', 'isCorrect' => false],
                ['text' => '0 + 8', 'isCorrect' => true],
            ],
            '5 + 5 = ?' => [
                ['text' => '6', 'isCorrect' => false],
                ['text' => '18', 'isCorrect' => false],
                ['text' => '10', 'isCorrect' => true],
                ['text' => '9', 'isCorrect' => false],
                ['text' => '0', 'isCorrect' => false],
            ],
            '6 + 6 = ?' => [
                ['text' => '3', 'isCorrect' => false],
                ['text' => '9', 'isCorrect' => false],
                ['text' => '0', 'isCorrect' => false],
                ['text' => '12', 'isCorrect' => true],
                ['text' => '5 + 7', 'isCorrect' => true],
            ],
            '7 + 7 = ?' => [
                ['text' => '5', 'isCorrect' => false],
                ['text' => '14', 'isCorrect' => true],
            ],
            '8 + 8 = ?' => [
                ['text' => '16', 'isCorrect' => true],
                ['text' => '12', 'isCorrect' => false],
                ['text' => '9', 'isCorrect' => false],
                ['text' => '5', 'isCorrect' => false],
            ],
            '9 + 9 = ?' => [
                ['text' => '18', 'isCorrect' => true],
                ['text' => '9', 'isCorrect' => false],
                ['text' => '17 + 1', 'isCorrect' => true],
                ['text' => '2 + 16', 'isCorrect' => true],
            ],
            '10 + 10 = ?' => [
                ['text' => '0', 'isCorrect' => false],
                ['text' => '2', 'isCorrect' => false],
                ['text' => '8', 'isCorrect' => false],
                ['text' => '20', 'isCorrect' => true],
            ],
        ];

        foreach ($questionsData as $questionText => $answersData) {
            $this->loadQuestion($manager, $questionText, $answersData);
        }

        $manager->flush();
    }

    private function loadQuestion(ObjectManager $manager, string $questionText, array $answersData): void
    {
        $question = new Question($questionText);

        foreach ($answersData as $data) {
            $answer = new Answer($data['text'], $data['isCorrect']);
            $question->addAnswer($answer);
        }

        $manager->persist($question);
    }
}
