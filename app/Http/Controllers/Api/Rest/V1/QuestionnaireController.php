<?php

namespace App\Http\Controllers\Api\Rest\V1;

use App\Exceptions\DomainExceptions\Entity\EntityNotFoundException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Questionnaire\CreateQuestionnaireRequest;
use App\Http\Requests\Questionnaire\UpdateQuestionnaireRequest;
use App\Http\Requests\Question\FindQuestionsByParamsRequest;
use App\Serializers\Normalizers\Question\QuestionsListNormalizer;
use App\Serializers\Normalizers\Questionnaire\QuestionnaireDetailNormalizer;
use App\UseCases\Auth\CheckAuthService;
use App\UseCases\Questionnaire\QuestionnaireService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class QuestionnaireController extends Controller
{
    private $checkAuthService;
    private $questionnaireService;
    private $questionnaireDetailNormalizer;
    private $questionsListNormalizer;

    public function __construct(
        CheckAuthService $checkAuthService,
        QuestionnaireService $questionnaireService,
        QuestionnaireDetailNormalizer $questionnaireDetailNormalizer,
        QuestionsListNormalizer $questionsListNormalizer
    )
    {
        $this->checkAuthService = $checkAuthService;
        $this->questionnaireService = $questionnaireService;
        $this->questionnaireDetailNormalizer = $questionnaireDetailNormalizer;
        $this->questionsListNormalizer = $questionsListNormalizer;
    }

    /**
     * @param CreateQuestionnaireRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function create(CreateQuestionnaireRequest $request): JsonResponse
    {
        $params = $request->all();

        DB::beginTransaction();

        try {
            $questionnaire = $this->questionnaireService->create($params);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json(
            $this->questionnaireDetailNormalizer->normalize($questionnaire)
        );
    }

    /**
     * @param UpdateQuestionnaireRequest $request
     * @param int $id
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Throwable
     */
    public function update(UpdateQuestionnaireRequest $request, int $id): JsonResponse
    {
        $params = $request->all();

        DB::beginTransaction();

        try {
            $questionnaire = $this->questionnaireService->update($params, $id);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json(
            $this->questionnaireDetailNormalizer->normalize($questionnaire)
        );
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws EntityNotFoundException
     * @throws Throwable
     */
    public function delete(int $id): JsonResponse
    {
        DB::beginTransaction();

        try {
            $this->questionnaireService->delete($id);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            throw $throwable;
        }

        return response()->json([], Response::HTTP_NO_CONTENT);
    }

    /**
     * @param FindQuestionsByParamsRequest $request
     * @return JsonResponse
     * @throws EntityNotFoundException
     */
    public function findByParams(FindQuestionsByParamsRequest $request): JsonResponse
    {
        $params = $request->all();

        $questionnaire = $this->questionnaireService->findByParams($params);

        return response()->json(
            $this->questionnaireDetailNormalizer->normalize($questionnaire)
        );
    }

    /**
     * @param int $id
     * @return JsonResponse
     * @throws EntityNotFoundException
     */
    public function getById(int $id): JsonResponse
    {
        $questionnaire = $this->questionnaireService->getById($id);

        return response()->json(
            $this->questionnaireDetailNormalizer->normalize($questionnaire)
        );
    }
}
