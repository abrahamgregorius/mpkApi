<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $news = News::get();

        return response()->json([
            'news' => $news
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required'],
            'content' => ['required'],
            'category_id' => ['required'],
        ]);

        if($validator->fails()){
            return response()->json([
                'message' => 'Invalid field',
                'errors' => $validator->errors()
            ], 422);
        }

        $slug = str($request->title)->slug();

        $news = News::create([
            'title' => $request->title,
            'slug' => $slug,
            'content' => $request->content,
            'user_id' => auth()->user()->id,
            'category_id' => $request->category_id,
        ]);

        return response()->json([
            'message' => 'Add news success',
            'news' => $news
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(News $news, string $slug)
    {
        $news = News::where('slug', $slug)->first();

        if(!$news) {
            return response()->json([
                'message' => 'News not found'
            ]);
        }

        return response()->json([
            'news' => $news
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(News $news)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, News $news)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(News $news)
    {
        //
    }
}
