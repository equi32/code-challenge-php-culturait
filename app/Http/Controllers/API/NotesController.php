<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Note;
use App\Tag;

use App\Http\Resources\NoteResource;

class NotesController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //Busco los registro
        $records = Note::latest()
            ->when(isset($request->start), function($query) use ($request){
                if($request->start > 0){
                    $query->offset($request->start);
                }
                //Return
                return $query;
            })
            ->limit(5)
            ->get();
        //Genero el resource
        $data = NoteResource::collection($records);
        //Envío respuesta
        return $this->sendResponse($data, trans('api.get_success'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //Genero y valido
        $this->validate($request, [
            'title' => 'required|max:150|unique:notes,title',
            'description' => 'nullable',
            'category' => 'required|in:'.implode(',', config('categories')),
            'status' => 'required|in:0,1',
            'tags' => 'array'
        ]);
        //Tomo los datos
        $object['title'] = $request->title;
        $object['description'] = $request->description;
        $object['category'] = $request->category;
        $object['status'] = $request->status;
        $object['tags'] = count($request->tags) > 0 ? implode("|", $request->tags) : null;
        $object['user_id'] = auth()->user()->id;
        //Guardo los datos
        $note = Note::create($object);
        //Genero el resource
        $data = new NoteResource($note);
        //Envío respuesta
        return $this->sendResponse($data, trans('api.get_success'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function list_categories()
    {
        //Busco las categorías
        $data = config('categories');
        //Envío respuesta
        return $this->sendResponse($data, trans('api.get_success'));
    }
}
