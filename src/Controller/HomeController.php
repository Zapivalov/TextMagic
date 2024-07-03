<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route(
    '/',
    name: 'test_home',
    methods: [Request::METHOD_GET],
)]
final class HomeController extends AbstractController
{
    public function __invoke(): Response
    {
        return $this->render('test/home.html.twig');
    }
}
