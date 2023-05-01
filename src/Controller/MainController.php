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
            if ($form->get('image')->getData() != null) {
                $file = $form->get('image')->getData();
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($this->getParameter('article_images'), $fileName);
                $article->setImage($fileName);
            }
            $article->setCreatedAt(new \DateTimeImmutable());
            $article->setCreatedBy($this->getUser());

            $articleRepository->save($article, true);
        }

        return $this->render('main/index.html.twig', [
            'articles' => $articleRepository->findAll(),
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'app_search')]
    public function search(Request $request, ArticleRepository $articleRepository)
    {
        $query = $request->query->get('search');
        $articles = $articleRepository->findBySearch($query);
        // Perform your search logic here...

        return $this->render('main/search.html.twig', [
            'query' => $query,
            'articles' => $articles,
        ]);
    }
}
