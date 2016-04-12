<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Translation;
use App\Word;

class TranslationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ($body = \Request::input('body')) {
            $translation = Translation::where('body', $body)->first();

            if ($translation) {
                $response = response()->json([['id' => $translation->getId(), 'body' => $translation->body]]);
            } else {
                $response = response()->json(['errors' => ['The translation hasn\'t found.']], 404);
            }
        } else if ($autocomplete = \Request::input('autocomplete')) {
            $translations = Translation::select('body') ->where('body', 'LIKE', "$autocomplete%")
                                                        ->take(\Request::header('Limit') ?: 5)
                                                        ->get()
                                                        ->lists('body');

            if (count($translations) > 0) {
                $response = response()->json($translations);
            } else {
                $response = response()->json(['errors' => ['The matched translations haven\'t found.']], 404);
            }
        } else {
            $result = Translation::paginate(\Request::header('Limit') ?: 10);

            $headers['Current-Page'] = $result->currentPage();
            $headers['Last-Page'] = $result->lastPage();
            $translations = [];
            foreach ($result as $key => $item) {
                $translations[$key]['id'] = $item->getId();
                $translations[$key]['body'] = $item->body;
            }

            if (count($translations) > 0) {
                $response = response()->json($translations, 200, $headers);
            } else {
                $response = response()->json(['errors' => ['There aren\'t translations.']], 404);
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
                'body' => 'required|unique:translations,body',
            ]
        );

        if ($validator->passes()) {
            $translation = Translation::create(['body' => $request->input('body')]);

            $response = response()->json(['id' => $translation->getId()], 201);
        } else {
            $response = response()->json(['errors' => $validator->messages()->all()], 400);
        }

        return $response;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Translation $translation
     *
     * @return \Illuminate\Http\Response
     */
    public function show($translation)
    {
        echo('translation show');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Translation $translation
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $translation)
    {
        echo('translation update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Translation $translation
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($translation)
    {
        echo('translation destroy');
    }
}
