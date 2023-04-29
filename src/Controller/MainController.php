<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Like;
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

    #[Route('/profile', name: 'app_profile')]
    public function profile(Request $request, UserRepository $userRepository, ArticleRepository $articleRepository): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfilePictureType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePicture = $form->get('profilePicture')->getData();
            if ($profilePicture) {
                if ($user->getProfilePicture() !== null) {
                    unlink($this->getParameter('profile_pictures_directory') . '/' . $user->getProfilePicture());
                }
                $originalFilename = pathinfo($profilePicture->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $profilePicture->guessExtension();
                $profilePicture->move(
                    $this->getParameter('profile_pictures_directory'),
                    $newFilename
                );
                $user->setProfilePicture($newFilename);
                $userRepository->save($user, true);
            }
        }
        return $this->render('main/profile.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'articles' => $articleRepository->findAll(),
        ]);
    }

    #[Route('/article/{id}', name: 'app_article')]
    public function article(Article $article): Response
    {
        return $this->render('main/article-details.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/article/{id}/delete', name: 'app_article_delete')]
    public function articleDelete(Article $article, ArticleRepository $articleRepository): Response
    {
        if ($this->getUser() == $article->getCreatedBy()) {
            $articleRepository->remove($article, true);
            $this->addFlash('success', 'Article supprimé avec succès !');
        }
        return $this->redirectToRoute('app_main');
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
}
