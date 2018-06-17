<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Answer
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
    private $text;
    
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", cascade={"persist", "remove"}, inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;
    
    /**
     * @ORM\Column(type="boolean")
     */
    private $correct;
    
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
     * @return Answer
     */
    public function setText(string $text): self
    {
        $this->text = $text;
        
        return $this;
    }
    
    /**
     * @return Question|null
     */
    public function getQuestion(): ?Question
    {
        return $this->question;
    }
    
    /**
     * @param Question $question
     * @return Answer
     */
    public function setQuestion(Question $question): self
    {
        $this->question = $question;
        
        return $this;
    }
    
    /**
     * @return bool|null
     */
    public function getCorrect(): ?bool
    {
        return $this->correct;
    }
    
    /**
     * @param bool $correct
     * @return Answer
     */
    public function setCorrect(bool $correct): self
    {
        $this->correct = $correct;
        
        return $this;
    }
}
