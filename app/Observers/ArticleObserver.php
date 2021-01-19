<?php

namespace App\Observers;

use App\Models\Article\Article;
use App\Models\Article\Questionnaire;

class ArticleObserver
{
    public function created(Article $article)
    {
        preg_match_all('/{QUEST_(\d+)}/', $article->content, $regexResult);

        $questionnaireIdentifiers = array_pop($regexResult);

        foreach ($questionnaireIdentifiers ?? [] as $questionnaireIdentifier) {
            /* @var Questionnaire $questionnaire */
            $questionnaire = Questionnaire::query()->find($questionnaireIdentifier);

            if ($questionnaire === null) {
                continue;
            }

            $questionnaire->article_id = $article->id;

            $questionnaire->save();
        }
    }

    public function updated(Article $article)
    {
        preg_match_all('/{QUEST_(\d+)}/', $article->content, $regexResult);

        $questionnaireIdentifiers = array_pop($regexResult);

        /* @var Questionnaire $questionnaire */
        $questionnaire = $article->questionnaire()->first();

        foreach ($questionnaireIdentifiers as $questionIdentifier) {
            if ($questionnaire->id === $questionIdentifier) {
                continue;
            }

            /* @var Questionnaire $questionnaireEntity */
            $questionnaireEntity = Questionnaire::query()->find($questionIdentifier);

            if ($questionnaireEntity === null) {
                continue;
            }

            $questionnaireEntity->article_id = $article->id;

            $questionnaireEntity->save();
        }
    }
}
