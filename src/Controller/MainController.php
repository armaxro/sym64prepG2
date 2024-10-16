<?php
// src/Controller/MainController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

// 😊😊
class MainController extends AbstractController
{
<<<<<<< HEAD
    #[Route('/', name: 'homapge')]
=======
    #[Route('/', name: 'homepage')]
>>>>>>> 74fbda159ddc5204fbc379e2e23c9e7129532819
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }
}
