<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->sort === 'oldest') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        return $query->get();
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'pewarta' => 'nullable|string',
            'editor' => 'nullable|string',
            'sumber' => 'nullable|string',
            'copyright' => 'nullable|string'
        ]);

        return Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $request->image,
            'pewarta' => $request->pewarta,
            'editor' => $request->editor,
            'sumber' => $request->sumber,
            'copyright' => $request->copyright
        ]);
    }

    public function show($id) {
        return Article::findOrFail($id);
    }

    public function update(Request $request, $id) {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|string',
            'pewarta' => 'nullable|string',
            'editor' => 'nullable|string',
            'sumber' => 'nullable|string',
            'copyright' => 'nullable|string'
        ]);

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $request->image,
            'pewarta' => $request->pewarta,
            'editor' => $request->editor,
            'sumber' => $request->sumber,
            'copyright' => $request->copyright
        ]);

        return $article;
    }

    public function destroy($id) {
        Article::findOrFail($id)->delete();
        return response()->json(['message' => 'Article deleted']);
    }
}
