<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    private $encoder;
    private $security;
    private $userRepository;
   
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, Security $security, UserRepository $userRepository)
    {
        $this->encoder = $passwordEncoder;
        $this->security = $security;
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/users/index", name="user_list")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function userList(Request $request, PaginatorInterface $paginator)
    {    
        $data = $this->userRepository->findBy([],
            ['lastName'  => 'desc']
        );

        $users = $paginator->paginate(
            $data,  // Données à paginer
            $request->query->getInt('page', 1),  // Numéro page en cours, 1 par défaut
            14   // Nombre d'utilisateurs/page
        );
        
        return $this->render('user/list.html.twig', [
            'users' => $users
        ]);
    }
    
    /**
     * @Route("/user/create", name="user_new")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return RedirectResponse|Response
     */
    public function userCreate(Request $request, EntityManagerInterface $manager)
    {       
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) 
        {          
            $user->setPassword($this->encoder->encodePassword($user, 'password'));
            $manager->persist($user);
            $manager->flush();

            $this->addFlash('success', "L'utilisateur a bien été ajouté.");
            if ($this->security->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('admin_user_list');
            }
            return $this->redirectToRoute('home');
        }
        return $this->render('user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}