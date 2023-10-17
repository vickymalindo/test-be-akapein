<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use JWTAuth;

class ArticleController extends Controller
{

    protected $user;
 
    public function __construct()
    {
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $articles = Article::all();

        if (sizeof($articles) === 0) {
            return response()->json([
              'message' => 'Failed or Empty Articles',
              'status' => 400,
            ]);
          }
    
          return response()->json([
            'message' => 'Success',
            'status' => 200,
            'data' => $articles
          ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
        $level = $this->user->level;
        if($level != 'admin') {
           return response()->json([
                'message' => 'Forbidden'
            ], 400);
        }

        $request->validate([
            'title' => 'required|max:50|unique:articles',
            'body' => 'required',
            'author' => 'required',
        ]);

        $article = Article::create([
            'title' => $request->title,
            'body' => $request->body,
            'author' => $request->author,
        ]);

        if (!$article) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Cannot created data'
            ], 400);
        } 
        return response()->json([
            'status' => 'success',
            'message' => 'Article created successfully',
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        $article = Article::find($id);

        if (!$article) {
          return response()->json([
            'message' => 'article not found',
            'status' => 400,
          ]);
        }

        return response()->json([
            'message' => 'article found',
            'data' => $article,
            'status' => 200,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Article $article)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //
        $level = $this->user->level;
        if($level != 'admin') {
           return response()->json([
                'message' => 'Forbidden'
            ], 400);
        }

        $article = Article::find($id);

        if(!$article) {
          return response()->json([
            'message' => 'article cannot find',
            'status' => 400,
          ]);
        }

        $data = $article->update([
          $article->title => $request->title,
          $article->body => $request->body,
          $article->author => $request->author
        ]);

        if (!$data) {
          return response()->json([
            'message' => 'article cannot updated',
            'status' => 400,
          ]);
        }
          return response()->json([
            'message' => 'article successfully updated',
            'status' => 200,
          ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
        $level = $this->user->level;
        if($level != 'admin') {
           return response()->json([
                'message' => 'Forbidden'
            ], 400);
        }
        $article = Article::find($id);
        $article->delete();

        return response()->json([
          'message' => 'delete successfully',
          'status' => 200
        ]);
    }
}
