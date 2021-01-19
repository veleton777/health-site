<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(
    ['prefix' => 'v1', 'namespace' => 'Api\Rest\V1', 'middleware' => 'api'], static function () {

    Route::group(['prefix' => 'admin'], static function () {
        Route::group(['prefix' => 'questionnaires'], static function () {
            Route::get('', 'QuestionnaireController@findByParams');
            Route::post('', 'QuestionnaireController@create');
            Route::get('{questionnaire_id}', 'QuestionnaireController@getById')->where([
                'questionnaire_id' => '[0-9]+'
            ]);
            Route::put('{questionnaire_id}', 'QuestionnaireController@update')->where([
                'questionnaire_id' => '[0-9]+'
            ]);
            Route::delete('{questionnaire_id}', 'QuestionnaireController@delete')->where([
                'questionnaire_id' => '[0-9]+'
            ]);
        });
    });

    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('code/verify', 'AuthController@verifyCode');
    Route::post('code/request', 'AuthController@requestPhoneVerification');

    Route::group(
        ['prefix' => 'user'], static function () {
        Route::get('profile', 'AuthController@getProfile');
        Route::put('profile', 'AuthController@updateProfile');

        Route::group(
            ['prefix' => 'favorites'], static function () {
            Route::get('', 'FavoriteController@findByParams');
            Route::post('{article_id}', 'FavoriteController@add')->where([
                'article_id' => '[0-9]+'
            ]);
            Route::delete('{article_id}', 'FavoriteController@delete')->where([
                'article_id' => '[0-9]+'
            ]);
        }
        );

        Route::post('like/{article_id}', 'LikeController@like')->where([
            'article_id' => '[0-9]+'
        ]);

        Route::post('recommend/{article_id}', 'RecommendationController@recommend')->where([
            'article_id' => '[0-9]+'
        ]);
    }
    );

    Route::group(
        ['prefix' => 'categories'], static function () {
        Route::get('', 'CategoryController@getAll');
    }
    );
    Route::group(
        ['prefix' => 'articles'], static function () {
        Route::get('', 'ArticleController@findByParams');
        Route::get('first', 'ArticleController@getFirstByParams');
        Route::get('{article_id}', 'ArticleController@getById')->where([
            'article_id' => '[0-9]+'
        ]);

        Route::post('answers/{answer_id}', 'AnswerController@check');
    }
    );
}
);
