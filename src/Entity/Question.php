<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Question
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="text")
     */
    private $text;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Quiz", cascade={"persist"})
     * @ORM\JoinColumn(name="quiz_id", referencedColumnName="id")
     */
    private $quiz;
    
    /**
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question", cascade={"all"})
     */
    private $answers;
    
    /**
     * Question constructor.
     */
    public function __construct()
    {
        $this->answers = new ArrayCollection();
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
    public function getText(): ?string
    {
        return $this->text;
    }
    
    /**
     * @param string $text
     * @return Question
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        
        return $this;
    }
    
    /**
     * @return Quiz|null
     */
    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }
    
    /**
     * @param Quiz $quiz
     * @return Question
     */
    public function setQuiz(Quiz $quiz): self
    {
        $this->quiz = $quiz;
        
        return $this;
    }
    
    /**
     * @return Collection|Answer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }
    
    /**
     * @param Answer $answer
     * @return Question
     */
    public function addAnswer(Answer $answer): self
    {
        if (false === $this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }
        
        return $this;
    }
    
    /**
     * @param Answer $answer
     * @return Question
     */
    public function removeAnswer(Answer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            // set the owning side to null (unless already changed)
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }
        
        return $this;
    }
}
