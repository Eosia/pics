<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
  Photo,
};
use Cache;

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

        $photo = Photo::find(37)->delete();


        //ne prend que les photos actives
        //$photos = Photo::with('album.user')->orderByDesc('created_at')->paginate();

        // requete les photos et les garde en cache tant que pas mise à jour
        $currentPage = request()->query('page', 1);
        $photos = Cache::rememberForever('photos_'.$currentPage, function() {
            return Photo::with('album.user')->orderByDesc('created_at')->paginate();
        });

        $data = [
            'title'=>'Photos libres de droit - '.config('app.name'),
            'description'=>'',
            'heading'=>config('app.name'),
            'photos'=>$photos,
        ];

        return view('home.index', $data);

    }
}
