<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\CreateArticleType;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/new', name: 'app_create_article')]
    public function index(Request $request, ArticleRepository $articleRepository): RedirectResponse
    {

        return $this->redirectToRoute('app_main');
    }
}

