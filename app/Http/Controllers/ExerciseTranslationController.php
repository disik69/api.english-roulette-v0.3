<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Exercise;
use App\Translation;
use App\Word;

class ExerciseTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Exercise $exercise
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Exercise $exercise)
    {
        $result = $exercise->translations;

        $translations = [];
        foreach ($result as $key => $item) {
            $translations[$key]['id'] = $item->getId();
            $translations[$key]['body'] = $item->body;
        }

        if (count($translations) > 0) {
            $response = response()->json($translations);
        } else {
            $response = response()->json(['errors' => ['There aren\'t translations.']], 404);
        }

        return $response;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Exercise $exercise
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Exercise $exercise)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'translation_id' => 'required|exists:translations,id',
            ]
        );

        if ($validator->passes()) {
            $translationId = $request->input('translation_id');

            if (! $exercise->translations()->find($translationId)) {
                if ($translation = $exercise->word->translations()->find($translationId)) {
                    $exercise->translations()->save($translation);

                    $response = response('This translation has added to the exercise.', 201);
                } else {
                    $response = response()->json(['errors' => ['This translation hasn\'t found in the exercise word.']], 404);
                }
            } else {
                $response = response()->json(['errors' => ['This translation already belongs the exercise.']], 400);
            }
        } else {
            $response = response()->json(['errors' => $validator->messages()->all()], 400);
        }

        return $response;
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Exercise $exercise
     * @param  \App\Translation $translation
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exercise $exercise, Translation $translation)
    {
        if (count($exercise->translations) > 1) {
            if ($exercise->translations()->find($translation->id)) {
                $exercise->translations()->detach($translation);

                $response = response('This translation has deleted.');
            } else {
                $response = response()->json(['errors' => ['This translation hasn\'t found in the exercise word.']], 404);
            }
        } else {
            $response = response()->json(['errors' => ['Exercise can\'t exist without translations.']], 400);
        }

        return $response;
    }
}
