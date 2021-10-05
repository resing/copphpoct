<?php

namespace App\Service;

use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use App\Repository\TagRepository;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Security;

class StatisticsUser
{
    private QuestionRepository $questionRepository;
    private AnswerRepository $answerRepository;
    private LoggerInterface $logger;
    private Security $security;
    private TagRepository $tagRepository;

    public function __construct(
        QuestionRepository $questionRepository ,
        AnswerRepository $answerRepository ,
        LoggerInterface $logger ,
        Security $security ,
        TagRepository $tagRepository
    ) {
        $this->questionRepository = $questionRepository;
        $this->answerRepository = $answerRepository;
        $this->logger = $logger;
        $this->security = $security;
        $this->tagRepository = $tagRepository;
    }

    public function avgQuestion()
    {
        $questionNumbers = $this->questionRepository->findBy(['user' => $this->security->getUser()]);
        $count = count($questionNumbers);
        if(0 === $count) {
            return 0;
        }
        
        $sum = array_sum($questionNumbers, 'voted');
        return round($sum/$count, 1);
    }
}
