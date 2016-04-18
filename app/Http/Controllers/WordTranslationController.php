<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Word;
use App\Translation;

class WordTranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param \App\Word $word
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Word $word)
    {
        $result = $word->translations;

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
     * @param \Illuminate\Http\Request  $request
     * @param \App\Word $word
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Word $word)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'translation_id' => 'required|exists:translations,id',
            ]
        );

        if ($validator->passes()) {
            if (is_null($word->position)) {
                $translationId = $request->input('translation_id');

                if (! $word->translations()->find($translationId)) {
                    $word->translations()->attach($translationId);

                    $response = response('This translation has added to the word.', 201);
                } else {
                    $response = response()->json(['errors' => ['This translation already belongs the word.']], 400);
                }
            } else {
                $response = response()->json(['errors' => ['You have attempted to bind a translation with a non-custom word']], 400);
            }
        } else {
            $response = response()->json(['errors' => $validator->messages()->all()], 400);
        }

        return $response;
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Word  $word
     * @param  \App\Translation  $translation
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Word $word, Translation $translation)
    {
        echo('word.translation destroy');
    }
}
