<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 */
class Quiz
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;
    
    /**
     * @ORM\OneToMany(targetEntity="Question", mappedBy="quiz", cascade={"all"})
     * @var Question[]|Collection
     */
    private $questions;
    
    /**
     * Quiz constructor.
     */
    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @return null|string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }
    
    /**
     * @param string $title
     * @return Quiz
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;
        
        return $this;
    }
    
    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }
    
    /**
     * @param Question $question
     * @return Quiz
     */
    public function addQuestion(Question $question): self
    {
        if (false === $this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setQuiz($this);
        }
        
        return $this;
    }
    
    /**
     * @param Question $question
     * @return Quiz
     */
    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            if ($question->getQuiz() === $this) {
                $question->setQuiz(null);
            }
        }
        
        return $this;
    }
}
