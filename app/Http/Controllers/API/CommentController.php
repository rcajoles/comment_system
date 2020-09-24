<?php

namespace App\Http\Controllers\API;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $all = Comment::query()
                        ->orderBy('id', 'desc')
                        ->get();

        $response = [
            'data' => $all
        ];

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $all = $request->all();

        $validator = Validator::make($all, [
            "parent" => "integer|nullable",
            "level" => "integer",
            "name" => "required|string|max:255|regex:/^[a-zA-Z0-9\s]+$/",
            "comment" => "required|string|max:255",
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            return response()->json([
                'status' => 400,
                'errors' => $errors->all()
            ]);
        }

        $all = $request->all();

        $all['comment'] = strip_tags($all['comment']);

        $comment = Comment::create($request->all());

        $response = [
            'data' => $comment,
            'status' => 200
        ];

        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }
}
