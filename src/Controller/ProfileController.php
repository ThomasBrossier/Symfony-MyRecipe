<?php

namespace App\Controller;

use App\Form\ProfileAvatarType;
use App\Form\ProfileType;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('front/profile/dashboard.html.twig', [
        ]);
    }
    #[Route('/account', name: 'app_profile_account')]
    public function account( Request $request, ProfileRepository $profileRepository): Response
    {
        $profile = $profileRepository->findOneBy(['user' => $this->getUser()],) ;

        $profile_form = $this->createForm(ProfileType::class,$profile);
        $profile_form->handleRequest($request);
        if($profile_form->isSubmitted() && $profile_form->isValid()){
            $profileRepository->save($profile,true);
            $this->addFlash('success', "Profil modifié");
            return $this->redirectToRoute('app_profile_account');
        }
        $avatar_form = $this->createForm(ProfileAvatarType::class, $profile);
        $avatar_form->handleRequest($request);
        if($avatar_form->isSubmitted() && $avatar_form->isValid()){
            $profile->setUpdatedAt(new \DateTimeImmutable());
            $profileRepository->save($profile, true);
            return $this->redirectToRoute('app_profile_account');
        }
        return $this->render('front/profile/account.html.twig', [
            'avatarForm' => $avatar_form->createView(),
            'profileForm' => $profile_form->createView(),
        ]);
    }
    #[Route('/account/edit/{i}', name: 'app_profile_account_edit')]
    public function account_edit(): Response
    {
        return $this->render('front/profile/dashboard.html.twig', [
        ]);
    }
}
