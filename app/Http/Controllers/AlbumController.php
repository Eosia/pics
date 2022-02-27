<?php

namespace App\Http\Controllers;

use App\Models\{
    Album, Category, Tag
};
use Illuminate\Http\Request;
use App\Http\Requests\AlbumRequest;
use DB, Auth;
use Illuminate\Validation\ValidationException;


class AlbumController extends Controller
{

    public function __construct(){
        $this->middleware(['auth', 'verified'])->except('show');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() // liste des albums de l'utilisateur connecté
    {
        //
        $albums = auth()->user()->albums()
            ->with('photos', fn($query) => $query->withoutGlobalScope('active')->orderByDesc('created_at'))->orderByDesc('updated_at')->paginate();

        $data = [
            'title' => $description = 'Mes albums',
            'description' => $description,
            'albums' => $albums,
            'heading' => $description,
        ];
        return view('album.index', $data);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $title=$description=$heading='Ajouter un nouvel album - '.config('app.name');
        return view('album.create', compact('title', 'description', 'heading'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AlbumRequest $request)
    {
        //
        DB::beginTransaction();

        try{
            $album = Auth::user()->albums()->create($request->validated());

            $categories = explode(',', $request->categories);

            $categories = collect($categories)->filter(function($value, $key){
                return $value != ' ';
            })->all();

            foreach($categories as $cat){
                $category = Category::firstOrCreate(['name' => trim($cat)]);
                $album->categories()->attach($category->id);
            }

            $tags = explode(',', $request->tags);

            $tags = collect($tags)->filter(function($value, $key){
                return $value != ' ';
            })->all();

            foreach($tags as $t){
                $tag = Tag::firstOrCreate(['name' => trim($t)]);
                $album->tags()->attach($tag->id);
            }

        }
        catch(ValidationException $e) {
            DB::rollback();
            dd($e->getErrors());
        }

        DB::commit();

        $success = 'Album ajouté.';
        $redirect = route('photos.create', [$album->slug]);
        return $request->ajax()
            ? response()->json(['success' => $success, 'redirect' => $redirect])
            : redirect($redirect)->withSuccess($success);


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Album $album)
    {
        //
    }
}
