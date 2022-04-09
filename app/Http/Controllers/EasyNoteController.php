<?php

namespace App\Http\Controllers;

use App\Models\EasyNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use App\Models\DB;

class EasyNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $user;
    
    public function __construct(){
        $this->middleware('auth:api');
        $this->user = $this->guard()->user();
    }


    public function index()
    {
        //
        $notes = $this->user->easynotes()->get(['author','title','body']);
        return response()->json($notes->toArray());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'author' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ], 400);
        }

        $notes = EasyNote::select("*")->where(
            'title', $request->title)->where('author', $request->author)->where('created_by', $this->user->id)->get();
       /*$note = new EasyNote();
        $note->title = $request->title;
        $note->author = $request->author;
        $note->body = $request->body;
        $note->description = $request->description;

        if($this->user->easynotes()->save($note)){
            return response()->json([
                'status' => true,
                'note' => "success"
            ]);
        }*/
       if($notes->count() > 0){
            return response()->json([
                "message" => "update"
            ]);
        }else{
            $note = new EasyNote();
            $note->title = $request->title;
            $note->author = $request->author;
            $note->body = $request->body;
            $note->description = $request->description;

            if($this->user->easynotes()->save($note)){
                return response()->json([
                    'status' => true,
                    'message' => "success"
                ]);
            }
        }
       // return response()->json($notes);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EasyNote  $easyNote
     * @return \Illuminate\Http\Response
     */
    public function show(EasyNote $easyNote)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EasyNote  $easyNote
     * @return \Illuminate\Http\Response
     */
    public function edit(EasyNote $easyNote)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EasyNote  $easyNote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EasyNote $easyNote)
    {
        //
        $validator = Validator::make($request->all(), [
            'author' => 'required|string',
            'body' => 'required|string',
            'title' => 'required|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ], 400);
        }
        $easyNote->title = $request->title;
        $easyNote->author = $request->author;
        $easyNote->body = $easyNote->body.",".$request->body;
        $easyNote->description = $request->description;

        if($this->user->easynotes()->save($easyNote)){
            return response()->json([
                'status' => true,
                'note' => "success"
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => "note can not be saved"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EasyNote  $easyNote
     * @return \Illuminate\Http\Response
     */
    public function destroy(EasyNote $easyNote)
    {
        //
        if($easyNote->delete()){
            return response()->json([
                'status' => true,
                'note' => $easyNote
            ]);
        }else{
            return response()->json([
                'status' => false,
                'message' => "note can not be saved"
            ]);
        }
    }

    public function guard(){
        return Auth::guard();
    }
}
