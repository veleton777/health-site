<?php


namespace App\UseCases\Questionnaire;


use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Models\Article\AnswerVariant;
use App\Models\Article\Question;
use App\Models\Article\Questionnaire;
use App\UseCases\AppUser\UserService;
use App\UseCases\Article\ArticleService;
use Exception;
use Illuminate\Database\Eloquent\Model;

class QuestionnaireService
{
    private $articleService;
    private $userService;

    public function __construct(ArticleService $articleService, UserService $userService)
    {
        $this->articleService = $articleService;
        $this->userService = $userService;
    }

    /**
     * @param array $params
     * @return Questionnaire
     */
    public function create(array $params): Questionnaire
    {
        $questionnaire = new Questionnaire();
        $questionnaire->save();

        foreach ($params['questions'] ?? [] as $questionParameter) {
            $questionEntity = new Question();
            $questionEntity->title = $questionParameter['question'];
            $questionEntity->save();

            $questionnaire->questions()->save($questionEntity);

            foreach ($questionParameter['answers'] ?? [] as $answerParameter) {
                /* @var AnswerVariant $answerEntity */
                $answerEntity = $questionEntity->answers()->create($answerParameter);

                if ($answerEntity->is_right && !$questionEntity->is_right_answer) {
                    $questionEntity->is_right_answer = true;
                    $questionEntity->save();
                }
            }
        }

        return $questionnaire;
    }

    /**
     * @param array $params
     * @param int $questionId
     * @return Questionnaire
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function update(array $params, int $questionId): Questionnaire
    {
        $questionnaire = $this->getById($questionId);

        foreach ($params['questions'] ?? [] as $questionParameter) {
            if ($questionParameter['id'] ?? null) {
                $questionEntity = Question::getById($questionParameter['id']);
                $questionEntity->title = $questionParameter['question'];
                $questionEntity->save();
            } else {
                $questionEntity = new Question();
                $questionEntity->title = $questionParameter['question'];
                $questionEntity->save();

                $questionnaire->questions()->save($questionEntity);
            }

            if ($questionParameter['delete_flag'] ?? null) {
                $questionEntity->answers()->delete();
                $questionEntity->delete();
                continue;
            }

            foreach ($questionParameter['answers'] ?? [] as $answerParameter) {
                if ($answerParameter['id'] ?? null) {
                    $answerEntity = AnswerVariant::getById($answerParameter['id']);
                    $answerEntity->fill($answerParameter);
                    $answerEntity->save();
                } else {
                    $answerEntity = $questionEntity->answers()->create($answerParameter);
                }

                if ($answerParameter['delete_flag'] ?? null) {
                    $answerEntity->delete();
                    continue;
                }

                if ($answerEntity->is_right && !$questionEntity->is_right_answer) {
                    $questionEntity->is_right_answer = true;
                    $questionEntity->save();
                }
            }
        }

        return $questionnaire;
    }

    /**
     * @param int $id
     * @throws EntityNotFoundException
     * @throws Exception
     */
    public function delete(int $id): void
    {
        $questionnaire = $this->getById($id);

        /* @var Question[] $questions */
        $questions = $questionnaire->questions()->get();

        foreach ($questions as $question) {
            $question->answers()->delete();
        }

        $questionnaire->questions()->delete();
        $questionnaire->delete();
    }

    /**
     * @param array $params
     * @return Questionnaire|Model
     * @throws EntityNotFoundException
     */
    public function findByParams(array $params): ?Questionnaire
    {
        $article = $this->articleService->getById($params['article_id']);

        return $article->questionnaire()->first();
    }

    /**
     * @param int $questionnaireId
     * @return Questionnaire|Model
     * @throws EntityNotFoundException
     */
    public function getById(int $questionnaireId): Questionnaire
    {
        $questionnaire = Questionnaire::query()->find($questionnaireId);

        if ($questionnaire === null) {
            throw new EntityNotFoundException('Такого опросника не существует!');
        }

        return $questionnaire;
    }
}
