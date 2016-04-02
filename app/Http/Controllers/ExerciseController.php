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
        $readingFlag = \Request::get('reading');
        $memoryFlag = \Request::get('memory');
        $checkFlag = \Request::get('check');

        if ($readingFlag || $memoryFlag || $checkFlag) {
            $lessonSize = $user->lesson_size;

            if ($readingFlag) {
                $scope = $scope ->where('status', 'new')
                                ->where('reading', '!=', 0)
                                ->where('memory', '!=', 0);
            } else if ($memoryFlag) {
                $scope = $scope ->where('status', 'new')
                                ->where('reading', 0)
                                ->where('memory', '!=', 0);
            } else if ($checkFlag) {
                $scope = $scope ->where('status', 'old')
                                ->where('check_at', '<', date_create());
            }

            $result = $scope    ->orderBy('updated_at', 'ASC')
                                ->take($lessonSize)
                                ->get();

            $exercises = [];
            foreach ($result as $key => $item) {
                $exercises[$key]['id'] = $item->getId();
                $exercises[$key]['word'] = $item->word->body;
                $exercises[$key]['ts'] = $item->word->ts;
                $exercises[$key]['position'] = $item->word->position ? $item->word->position->body : null;
                $exercises[$key]['reading'] = $item->getReading();
                $exercises[$key]['memory'] = $item->getMemory();
                $exercises[$key]['translation'] = [];

                foreach ($item->translations as $_key => $translation) {
                    $exercises[$key]['translation'][$_key]['id'] = $translation->getId();
                    $exercises[$key]['translation'][$_key]['body'] = $translation->body;
                }
            }

            if (count($exercises) > 0) {
                $response = response()->json($exercises);
            } else {
                $response = response()->json(['errors' => ['There aren\'t exercises.']], 404);
            }
        } else {
            if ($search = \Request::get('search')) {
                $scope = $scope ->join('words', 'words.id', '=', 'exercises.word_id')
                                ->where('words.body', 'LIKE', "$search%")
                                ->select('exercises.*');
            }

            $result = $scope    ->orderBy('updated_at', 'DESC')
                                ->paginate(\Request::get('limit') ?: 10);

            $page['current_page'] = $result->currentPage();
            $page['last_page'] = $result->lastPage();
            $page['data'] = [];
            foreach ($result as $key => $item) {
                $page['data'][$key]['id'] = $item->getId();
                $page['data'][$key]['word'] = $item->word->body;
                $page['data'][$key]['ts'] = $item->word->ts;
                $page['data'][$key]['position'] = $item->word->position ? $item->word->position->body : null;
                $page['data'][$key]['reading'] = $item->getReading();
                $page['data'][$key]['memory'] = $item->getMemory();
                $page['data'][$key]['translation'] = [];

                foreach ($item->translations as $_key => $translation) {
                    $page['data'][$key]['translation'][$_key]['id'] = $translation->getId();
                    $page['data'][$key]['translation'][$_key]['body'] = $translation->body;
                }
            }

            if (count($page['data']) > 0) {
                $response = response()->json($page);
            } else {
                $response = response()->json(['errors' => ['There aren\'t exercises.']], 404);
            }
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
            $word = Word::find($request->get('word_id'));

            if (! $user->exercises->contains('word_id', $word->id)) {
                if ($translation = $word->translations()->find($request->get('translation_id'))) {
                    $exercise = new Exercise();

                    $exercise->reading = $user->reading_count;
                    $exercise->memory = $user->memory_count;

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
     * @param  \App\Exercise  $exercise
     *
     * @return \Illuminate\Http\Response
     */
    public function show($exercise)
    {
        $result['id'] = $exercise->getId();
        $result['word'] = $exercise->word->body;
        $result['ts'] = $exercise->word->ts;
        $result['position'] = $exercise->word->position ? $exercise->word->position->body : null;
        $result['reading'] = $exercise->getReading();
        $result['memory'] = $exercise->getMemory();
        $result['memory'] = $exercise->getMemory();

        foreach ($exercise->translations as $key => $translation) {
            $result['translation'][$key]['id'] = $translation->getId();
            $result['translation'][$key]['body'] = $translation->body;
        }

        return response()->json($result);
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
        echo('exercise update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Exercise  $exercise
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($exercise)
    {
        $exercise->delete();

        return response('This exercise has deleted.', 200);;
    }
}
