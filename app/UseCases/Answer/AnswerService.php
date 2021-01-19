<?php


namespace App\UseCases\Answer;


use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Models\Article\AnswerVariant;
use App\UseCases\AppUser\UserService;
use App\UseCases\Article\ArticleService;
use Illuminate\Database\Eloquent\Model;

class AnswerService
{
    private $articleService;
    private $userService;

    public function __construct(ArticleService $articleService, UserService $userService)
    {
        $this->articleService = $articleService;
        $this->userService = $userService;
    }

    /**
     * @param int $answerId
     * @param int $userId
     * @throws EntityNotFoundException
     */
    public function check(int $answerId, int $userId): void
    {
        $user = $this->userService->getUserById($userId);

        $answer = $this->getAnswerById($answerId);

        $user->checkAnswer($answer->question_id, $answerId);

        $answer->count_votes += 1;
        $answer->save();
    }

    /**
     * @param int $answerId
     * @return AnswerVariant|Model
     * @throws EntityNotFoundException
     */
    public function getAnswerById(int $answerId): AnswerVariant
    {
        $answer = AnswerVariant::query()->find($answerId);

        if ($answer === null) {
            throw new EntityNotFoundException('Такого варианта ответа не существует!');
        }

        return $answer;
    }
}
