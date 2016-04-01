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
        if ($body = \Request::get('body')) {
            $result = Word::with('position', 'translations')->where('body', $body)->get();
            $exercises = \Auth::user()->exercises;

            $words = [];
            foreach ($result as $key => $item) {

                $words[$key]['id'] = $item->getId();
                $words[$key]['body'] = $item->body;
                $words[$key]['ts'] = $item->ts;
                $words[$key]['position'] = $item->position ? $item->position->body : null;
                $words[$key]['used'] = $exercises->contains('word_id', $item->id) ? true : false;
                $words[$key]['translation'] = [];

                foreach ($item->translations as $_key => $translation) {
                    $words[$key]['translation'][$_key]['id'] = $translation->getId();
                    $words[$key]['translation'][$_key]['body'] = $translation->body;
                }
            }

            if (count($words) > 0) {
                $response = response()->json($words);
            } else {
                $response = response()->json(['errors' => ['The word hasn\'t found.']], 404);
            }
        } else if ($autocomplete = \Request::get('autocomplete')) {
            $words = Word::select('body')   ->where('body', 'LIKE', "$autocomplete%")
                                            ->groupBy('body')
                                            ->take(\Request::get('limit') ?: 5)
                                            ->get()
                                            ->lists('body');

            if (count($words) > 0) {
                $response = response()->json($words);
            } else {
                $response = response()->json(['errors' => ['The matched words haven\'t found.']], 404);
            }
        } else {
            $result = Word::with('position')->paginate(\Request::get('limit') ?: 10);

            $page['current_page'] = $result->currentPage();
            $page['last_page'] = $result->lastPage();
            $page['data'] = [];
            foreach ($result as $key => $item) {
                $page['data'][$key]['id'] = $item->getId();
                $page['data'][$key]['body'] = $item->body;
                $page['data'][$key]['ts'] = $item->ts;
                $page['data'][$key]['position'] = $item->position ? $item->position->body : null;
            }

            if (count($page['data']) > 0) {
                $response = response()->json($page);
            } else {
                $response = response()->json(['errors' => ['There aren\'t words.']], 404);
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
                'body' => 'required',
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

                $response = response()->json(['id' => $word->getId()], 201);
            }
        } else {
            $response = response()->json(['errors' => $validator->messages()->all()], 400);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Word $word
     *
     * @return \Illuminate\Http\Response
     */
    public function show($word)
    {
        echo('word show');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Word $word
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $word)
    {
        echo('word update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Word $word
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($word)
    {
        echo('word destoy');
    }
}
