<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Translation;
use App\Position;
use App\Word;
use Illuminate\Database\QueryException;

class WordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo('word index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'body' => 'required',
                'translation_id' => 'sometimes|exists:translations,id',
                'via_dictionary' => 'sometimes|boolean',
            ]
        );

        if ($validator->passes()) {
            if ($request->get('via_dictionary')) {
                $definitions = \YaDictionary::lookup($request->get('body'));

                if ($definitions) {
                    foreach ($definitions as $definition) {
                        $partOfSpeech = $definition->getPartOfSpeech();

                        if (! $position = Position::where('body', $partOfSpeech)->first()) {
                            $position = Position::create(['body' => $partOfSpeech]);
                        }

                        if (! Word::where('body', $definition->getText())->where('position_id', $position->id)->first()) {
                            $word = new Word();

                            $word->body = $definition->getText();
                            $word->ts = $definition->getTranscription();
                            $word->position()->associate($position);

                            $word->save();

                            $translationIds = [];

                            foreach ($definition->getTranslations() as $yaTranslation) {
                                if (! $translation = Translation::where('body', $yaTranslation->getText())->first()) {
                                    $translation = Translation::create(['body' => $yaTranslation->getText()]);
                                }

                                $translationIds[] = $translation->id;
                            }

                            $word->translations()->attach($translationIds);
                        }
                    }

                    $response = response('This word has created from the dictionary', 201);
                } else {
                    $response = response()->json(['errors' => ['This word has not found in the dictionary.']], 404);
                }
            } else {
                $word = Word::create(['body' => $request->get('body')]);

                if ($translationId = $request->get('translation_id')) {
                    $word->translations()->attach($translationId);
                }

                $response = response()->json(['id' => $word->id], 201);
            }
        } else {
            $response = response()->json(['errors' => $validator->messages()->all()], 400);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        echo('word show');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        echo('word update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        echo('word destoy');
    }
}
