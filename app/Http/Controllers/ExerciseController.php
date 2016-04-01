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
        echo('exercise index');
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
            ]
        );

        if ($validator->passes()) {
            $user = \Auth::user();
            $wordId = $request->get('word_id');

            if (! $user->exercises->contains('word_id', $wordId)) {
                $exercise = new Exercise();

                $exercise->reading = $user->reading_count;
                $exercise->memory = $user->memory_count;

                $exercise->user()->associate($user);
                $exercise->word()->associate($wordId);

                $exercise->save();

                $response = response()->json(['id' => $exercise->getId()], 201);
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
        echo('exercise show');
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
        echo('exercise destroy');
    }
}
