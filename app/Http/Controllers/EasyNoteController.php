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
            $note = $notes[0];
            $body = $note->body.",".$request->body;

            $data = [
                'title' => $request->title,
                'body' => $body,
                'author' => $request->author,
                'description' => $request->description
            ];
            $notes = new EasyNote();
            $notes->title = $request->title;
            $notes->body = $body;
            $notes->author = $request->author;
            $notes->description = $request->description;
            return redirect()->to('api/notes/update',$data, $status = 302, $headers = ['method' => 'PUT'], $secure = null); 
            //return Http::patch("http://127.0.0.1:8000/api/notes/update", $data);
        }else{
            $note = new EasyNote();
            $note->title = $request->title;
            $note->author = $request->author;
            $note->body = $request->body;
            $note->description = $request->description;

            if($this->user->easynotes()->save($note)){
                return response()->json([
                    'status' => true,
                    'note' => "success"
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
            'text' => 'required|string',
            'title' => 'requires|string'
        ]);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'error' => $validator->errors()
            ], 400);
        }
        $easyNote->title = $request->title;
        $easyNote->author = $request->author;
        $easyNote->body = $request->body;
        $easyNote->description = $request->description;

        if($this->user()->easynotes()->save($easyNote)){
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
