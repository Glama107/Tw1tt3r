<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Like;
use App\Entity\User;
use App\Form\CreateArticleType;
use App\Form\ProfilePictureType;
use App\Repository\ArticleRepository;
use App\Repository\LikeRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(ArticleRepository $articleRepository, Request $request): Response
    {
        date_default_timezone_set('Europe/Paris');
        $article = new Article();
        $form = $this->createForm(CreateArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setCreatedAt(new \DateTimeImmutable());
            $article->setCreatedBy($this->getUser());

            $articleRepository->save($article, true);
        }

        return $this->render('main/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'form' => $form,
        ]);
    }
}
