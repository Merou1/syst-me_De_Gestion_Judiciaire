<?php 
// src/Controller/ProductController.php


namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Lawyer;
use App\Entity\Judge;
use App\Entity\Greffier;
use Doctrine\Common\Collections\Expr\Value;
use PhpParser\Node\Stmt\Echo_;
use App\Controller\DossierController;
use App\Controller\AudienceController;
use App\Controller\PreuvesDossierController;



class Gestion extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'signin_signup', methods: ['GET'])]
    public function signinsignup(): Response
    {
        return $this->render('signin_signup_page.html.twig');
    }
    #[Route('/lawyer_page', name: 'lawyer_page', methods: ['GET'])]
    public function lawyer_page(): Response
    {
        return $this->render('lawyer.html.twig');
    }
    #[Route('/greffier_page', name: 'greffier_page', methods: ['GET'])]
    public function greffier_page(): Response
    {
        return $this->render('greffier.html.twig');
    }
    #[Route('/judge_page', name: 'judge_page', methods: ['GET'])]
    public function judge_page(): Response
    {
        return $this->render('judge.html.twig');
    }

    #[Route('/signup', name: 'signup', methods: ['POST'])]
    public function signup(Request $request): Response
    {


        // nakhdou data men form
        $name = $request->request->get('name');
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmpassword = $request->request->get('confirmpassword');
        $role = $request->request->get('role');

        if (empty($name) || empty($email) || empty($password) || empty($confirmpassword) || empty($role)) {
            $this->addFlash('error', 'All fields are required.');
            return $this->redirectToRoute('signin_signup');
        }

        if ($password !== $confirmpassword) {
            $this->addFlash('error', 'Passwords do not match.');
            return $this->redirectToRoute('signin_signup');
        }

        $existingUser = $this->entityManager->getRepository(User::class)->findOneByEmail($email);
        if ($existingUser) {
            $this->addFlash('error', 'A user with this email already exists.');
            return $this->redirectToRoute('signin_signup');
        }

    
        //user ela hssab role 
        if ($role === 'lawyer') {
            $user = new Lawyer();
        } elseif ($role === 'judge') {
            $user = new Judge();
        } elseif ($role === 'greffier') {
            $user = new Greffier();
        } else {

        }

        // user jdid
        $user->setName($name);
        $user->setEmail($email);
        $user->setPassword(password_hash($password, PASSWORD_BCRYPT));

        $this->entityManager->persist($user);
        $this->entityManager->flush();
        $this->addFlash('success', 'Signup successful! Now you can sign in.');


    
    return $this->redirectToRoute('signin_signup');

    }

    #[Route('/signin', name: 'signin', methods: ['POST'])]
public function signin(Request $request, EntityManagerInterface $entityManager): Response
{
    $email = strtolower(trim($request->request->get('email')));
    $password = strtolower(trim($request->request->get('password')));

    if (empty($email) || empty($password)) {
        $this->addFlash('error', 'All fields are required.');
        return $this->redirectToRoute('signin_signup');
    }

    // Find user by email
    $user = $entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

    if (!$user ||  !password_verify($password, $user->getPassword())) {
        $this->addFlash('error', 'Invalid email or password.');
        return $this->redirectToRoute('signin_signup');
    }

    // Store the user's email in the session
    $session = $request->getSession();
    $session->set('user_email', $user->getEmail());
    $session->set('user_role', $user->getRole());

    switch ($user->getRole()) {
        case 'greffier':
            return $this->redirectToRoute('app_dossier_index');
        case 'judge':
            return $this->redirectToRoute('judge_page');
        case 'lawyer':
            return $this->redirectToRoute('app_preuves_dossier_index');
    }
}



}
?>