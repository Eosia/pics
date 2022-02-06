<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        //
        $data = [
            'title'=>'Photos libres de droit - '.config('app.name'),
            'description'=>'',
            'heading'=>config('app.name'),
        ];

        return view('home.index', $data);

    }
}
