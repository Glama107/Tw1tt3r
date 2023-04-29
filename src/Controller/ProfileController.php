<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilePictureType;
use App\Repository\ArticleRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
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

    #[Route('/profile/{pseudo}', name: 'app_profile_id')]
    public function profileId(User $user, ArticleRepository $articleRepository): Response
    {
        return $this->render('main/profile.html.twig', [
            'user' => $user,
            'articles' => $articleRepository->findAll(),
        ]);
    }
}