<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index() {
        return Article::all();
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
        ]);

        return Article::create($request->all());
    }

    public function show($id) {
        return Article::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $article = Article::findOrFail($id);
        $article->update($request->all());
        return $article;
    }

    public function destroy($id) {
        Article::findOrFail($id)->delete();
        return response()->json(['message' => 'Article deleted']);
    }
}
