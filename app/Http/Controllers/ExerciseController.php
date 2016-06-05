<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Exercise;
use App\Word;

class ExerciseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = \Auth::user();
        $scope = $user->exercises()->with('word.position', 'translations');
        $readingFlag = \Request::input('reading');
        $memoryFlag = \Request::input('memory');
        $checkFlag = \Request::input('check');
        $headers = [];

        if ($readingFlag || $memoryFlag || $checkFlag) {
            $lessonSize = $user->lesson_size;

            if ($readingFlag) {
                $scope ->where('status', 'new')
                       ->where('reading', '!=', 0)
                       ->where('memory', '!=', 0);
            } else if ($memoryFlag) {
                $scope ->where('status', 'new')
                       ->where('reading', 0)
                       ->where('memory', '!=', 0);
            } else if ($checkFlag) {
                $scope ->where('status', 'old')
                       ->where('check_at', '<', date_create());
            }

            $result = $scope    ->orderBy('updated_at', 'ASC')
                                ->take($lessonSize)
                                ->get();
        } else if (\Request::input('random')) {
            if ($excludedId = \Request::input('excluded_id')) {
                $scope->where('id', '!=', $excludedId);
            }

            $result = $scope    ->orderByRaw('RAND()')
                                ->take(\Request::header('Limit') ?: 5)
                                ->get();
        } else {
            if ($search = \Request::input('search')) {
                $scope = $scope ->join('words', 'words.id', '=', 'exercises.word_id')
                                ->where('words.body', 'LIKE', "$search%")
                                ->select('exercises.*');
            }

            $result = $scope    ->orderBy('updated_at', 'DESC')
                                ->paginate(\Request::header('Limit') ?: 10);

            $headers['Current-Page'] = $result->currentPage();
            $headers['Last-Page'] = $result->lastPage();
        }

        $exercises = [];
        foreach ($result as $key => $item) {
            $exercises[$key] = $item->view();
        }

        if (count($exercises) > 0) {
            $response = response()->json($exercises, 200, $headers);
        } else {
            $response = response()->json(['errors' => ['there aren\'t any exercises.']], 404);
        }

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'word_id' => 'required|exists:words,id',
                'translation_id' => 'required|exists:translations,id',
            ]
        );

        if ($validator->passes()) {
            $user = \Auth::user();
            $word = Word::find($request->input('word_id'));

            if (! $user->exercises()->where('word_id', $word->id)->first()) {
                if ($translation = $word->translations()->find($request->input('translation_id'))) {
                    $exercise = new Exercise();

                    $exercise->setNewStatus($user);

                    $exercise->user()->associate($user);
                    $exercise->word()->associate($word);

                    $exercise->save();

                    $exercise->translations()->attach($translation);

                    $response = response()->json(['id' => $exercise->getId()], 201);
                } else {
                    $response = response()->json(['errors' => ['This translation hasn\'t found in the exercise word.']], 404);
                }
            } else {
                $response = response()->json(['errors' => ['You already have this word in your exercises.']], 400);
            }
        } else {
            $response = response()->json(['errors' => $validator->messages()->all()], 400);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Exercise $exercise
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Exercise $exercise)
    {
        return response()->json($exercise->view());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Exercise  $exercise
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $exercise)
    {
        if ($request->input('up')) {
            if ($exercise->status == 'new') {
                if ($exercise->reading != 0) {
                    $exercise->reading--;
                } else if ($exercise->memory != 0) {
                    $exercise->memory--;

                    if ($exercise->memory == 0) {
                        $exercise->setOldStatus(\Auth::user());
                    }
                }

                $exercise->save();

                $response = response('The exercise has been upped.');
            } else {
                $response = response()->json(['errors' => ['The exercise have the OLD status.']], 400);
            }
        } else if ($request->input('new')) {
            $exercise->setNewStatus(\Auth::user());

            $exercise->save();

            $response = response('The NEW status has been defined.');
        } else if ($request->input('old')) {
            $exercise->setOldStatus(\Auth::user());

            $exercise->save();

            $response = response('The OLD status has been defined');
        } else {
            $response = response()->json(['errors' => ['The key hasn\'t defined.']], 400);
        }

        return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Exercise  $exercise
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exercise $exercise)
    {
        $exercise->delete();

        return response('This exercise has deleted.');
    }
}
