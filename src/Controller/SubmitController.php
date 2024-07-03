<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\TestSession;
use App\Factory\TestResultFactory;
use App\Service\TestResultProcessor;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/test/result/{id}',
    name: 'test_submit',
    methods: [Request::METHOD_GET, Request::METHOD_POST],
)]
final class SubmitController extends AbstractController
{
    public function __construct(
        private readonly TestResultProcessor $testResultProcessor,
        private readonly TestResultFactory $testResultFactory,
    ) {
    }

    public function __invoke(Request $request, TestSession $testSession):Response
    {
        try {
            $results = $this->testResultProcessor->processTestResults($request->query->all());
            $this->testResultFactory->create($testSession, $results);


            return $this->render('test/result.html.twig', [
                'results' => $results,
            ]);
        } catch (Exception) {

            return $this->render('error/error.html.twig');
        }
    }
}
