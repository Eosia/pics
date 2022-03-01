<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    Photo, Album
};

use App\Http\Requests\PhotoRequest;

class PhotoController extends Controller
{
    // ajout d'une photo à un album
    public function create(Album $album)
    {
        abort_if($album->user_id != auth()->id(), 403);

        $data = [
            'title'=>$description= 'Ajouter des photos à '. $album->title,
            'description'=>$description,
            'album'=>$album,
            'heading'=>$album->title,
        ];

        return view('photo.create', $data);

    }

    // sauveguarde d'une photo
    public function store(PhotoRequest $request, Album $album)
    {

        $request->validated();

    }

}
