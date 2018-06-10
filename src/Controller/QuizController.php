<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Form\QuizType;
use App\Repository\QuizRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quiz")
 */
class QuizController extends Controller
{
    /**
     * @Route("/", name="quiz_index", methods="GET")
     * @param QuizRepository $quizRepository
     * @return Response
     */
    public function index(QuizRepository $quizRepository): Response
    {
        return $this->render('quiz/index.html.twig', ['quizzes' => $quizRepository->findAll()]);
    }
    
    /**
     * @Route("/new", name="quiz_new", methods="GET|POST")
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $quiz = new Quiz();
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quiz);
            $em->flush();
            
            return $this->redirectToRoute('quiz_index');
        }
        
        return $this->render('quiz/new.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}", name="quiz_show", methods="GET")
     * @param Quiz $quiz
     * @return Response
     */
    public function show(Quiz $quiz): Response
    {
        return $this->render('quiz/show.html.twig', ['quiz' => $quiz]);
    }
    
    /**
     * @Route("/{id}/edit", name="quiz_edit", methods="GET|POST")
     * @param Request $request
     * @param Quiz $quiz
     * @return Response
     */
    public function edit(Request $request, Quiz $quiz): Response
    {
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quiz);
            $em->flush();
            
            return $this->redirectToRoute('quiz_edit', ['id' => $quiz->getId()]);
        }
        
        return $this->render('quiz/edit.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}", name="quiz_delete", methods="DELETE")
     * @param Request $request
     * @param Quiz $quiz
     * @return Response
     */
    public function delete(Request $request, Quiz $quiz): Response
    {
        if ($this->isCsrfTokenValid('delete' . $quiz->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($quiz);
            $em->flush();
        }
        
        return $this->redirectToRoute('quiz_index');
    }
}
