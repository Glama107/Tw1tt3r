<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Like;
use App\Form\CreateArticleType;
use App\Form\CreateCommentType;
use App\Repository\ArticleRepository;
use App\Repository\CommentRepository;
use App\Repository\LikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    #[Route('/article/{id}', name: 'app_article')]
    public function article(Article $article, Request $request, CommentRepository $commentRepository): Response
    {
        date_default_timezone_set('Europe/Paris');
        $comment = new Comment();
        $form = $this->createForm(CreateCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setCreatedBy($this->getUser());
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setCommentedPost($article);

            $commentRepository->save($comment, true);
        }

        return $this->render('article/article-details.html.twig', [
            'article' => $article,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/article/{id}/delete', name: 'app_article_delete')]
    public function articleDelete(Article $article, ArticleRepository $articleRepository, Request $request, LikeRepository $likeRepository): Response
    {
        if ($this->getUser() == $article->getCreatedBy()) {
            if ($article->getLikes() != null) {
                foreach ($article->getLikes() as $like) {
                    $likeRepository->remove($like, true);
                }
            }
            $articleRepository->remove($article, true);
            $this->addFlash('success', 'Article supprimé avec succès !');
        }
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    #[Route('/article/{id}/comment/{commentId}/delete', name: 'app_comment_delete')]
    public function commentDelete($commentId, CommentRepository $commentRepository, Request $request, LikeRepository $likeRepository): Response
    {
        $comment = $commentRepository->findOneBy(['id' => $commentId]);
        if ($this->getUser() == $comment->getCreatedBy()) {
            if ($comment->getLikes() != null) {
                foreach ($comment->getLikes() as $like) {
                    $likeRepository->remove($like, true);
                }
            }
            $commentRepository->remove($comment, true);
            $this->addFlash('success', 'Commentaire supprimé avec succès !');
        }
        $route = $request->headers->get('referer');
        return $this->redirect($route);
    }

    #[Route('/article/{id}/like', name: 'app_article_like', methods: ['POST'])]
    public function likeArticle(Article $article, LikeRepository $likeRepository): JsonResponse
    {
        $user = $this->getUser();
        if ($user === null) {
            return $this->json(['likes' => $article->getLikes()->count()]);
        }
        foreach ($article->getLikes() as $like) {
            if ($like->getLikedBy() === $user) {
                $likeRepository->remove($like, true);
                return $this->json(['likes' => $article->getLikes()->count()]);
            } else {
                $like = new Like();
                $like->setLikedBy($this->getUser());
                $like->setLikedPost($article);
                $like->setCreatedAt(new \DateTimeImmutable());

                $likeRepository->save($like, true);

                return $this->json(['likes' => $article->getLikes()->count() + 1]);
            }
        }
        $like = new Like();
        $like->setLikedBy($this->getUser());
        $like->setLikedPost($article);
        $like->setCreatedAt(new \DateTimeImmutable());

        $likeRepository->save($like, true);

        return $this->json(['likes' => $article->getLikes()->count() + 1]);
    }

    #[Route('/article/{id}/comment/{commentId}/like', name: 'app_comment_like', methods: ['POST'])]
    public function likeComment($commentId, Article $article, LikeRepository $likeRepository, CommentRepository $commentRepository): JsonResponse
    {
        $comment = $commentRepository->findOneBy(['id' => $commentId]);
        $user = $this->getUser();
        if ($user === null) {
            return $this->json(['likes' => $comment->getLikes()->count()]);
        }
        foreach ($comment->getLikes() as $like) {
            if ($like->getLikedBy() === $user) {
                $likeRepository->remove($like, true);
                return $this->json(['likes' => $comment->getLikes()->count()]);
            } else {
                $like = new Like();
                $like->setLikedBy($this->getUser());
                $like->setLikedComment($comment);
                $like->setCreatedAt(new \DateTimeImmutable());

                $likeRepository->save($like, true);

                return $this->json(['likes' => $comment->getLikes()->count() + 1]);
            }
        }
        $like = new Like();
        $like->setLikedBy($this->getUser());
        $like->setLikedComment($comment);
        $like->setCreatedAt(new \DateTimeImmutable());

        $likeRepository->save($like, true);

        return $this->json(['likes' => $comment->getLikes()->count() + 1]);
    }

}

