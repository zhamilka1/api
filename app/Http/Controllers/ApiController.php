<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;
use Mockery\Matcher\Not;

class ApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $response = Note::all();
        if($response->count()>0){
            return view('output', [
                'data' => $response->paginate($response->count())
            ]);
        } else {
            return response()->json('no_content', 204);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'initials' => ['required'],
            'email' => ['required', 'unique:notes'],
            'number' => ['required', 'unique:notes']
        ]);
        $path_where_save = $request->file('photo')->store('uploads, public');
        $path_to_photo = asset('/storage/'.$path_where_save);
        $note = Note::create([
            'initials' => $request['initials'],
            'number' => $request['number'],
            'email' => $request['email'],
            'company' => $request['company'],
            'birthday' => $request['birthday'],
            'photo_url' => $path_to_photo
        ]);
        return response()->json($note, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $selectedResultFromModel = Note::all()->where('id', $id);
        if ($selectedResultFromModel->count() > 0) {
            return response()->json($selectedResultFromModel, 200);
        } else {
            return response()->json('no_content', 204);
        }
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
        $note = Note::find($id)->update($request);
        return response()->json($note, 200);
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
}
