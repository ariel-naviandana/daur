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

//     public function store(Request $request) {
//         $request->validate([
//             'title' => 'required|string',
//             'content' => 'required|string',
//             'image_url' => 'nullable|string',
//         ]);
//
//         return Article::create($request->all());
//     }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'pewarta' => 'nullable|string',
            'editor' => 'nullable|string',
            'sumber' => 'nullable|string',
            'copyright' => 'nullable|string'
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $filename);
            $imageUrl = '/images/' . $filename;
        }

        return Article::create([
            'title' => $request->title,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'pewarta' => $request->pewarta,
            'editor' => $request->editor,
            'sumber' => $request->sumber,
            'copyright' => $request->copyright
        ]);
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $filename);
            return response()->json(['url' => '/images/' . $filename]);
        }
        return response()->json(['error' => 'No file uploaded'], 400);
    }

    public function show($id) {
        return Article::findOrFail($id);
    }

//     public function update(Request $request, $id) {
//         $article = Article::findOrFail($id);
//         $article->update($request->all());
//         return $article;
//     }

    public function update(Request $request, $id) {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif,svg|max:2048',
            'pewarta' => 'nullable|string',
            'editor' => 'nullable|string',
            'sumber' => 'nullable|string',
            'copyright' => 'nullable|string'
        ]);

        $imageUrl = $article->image_url;

        if ($request->hasFile('image')) {
            $filename = time() . '.' . $request->image->getClientOriginalExtension();
            $request->image->move(public_path('images'), $filename);
            $imageUrl = '/images/' . $filename;
        }

        $article->update([
            'title' => $request->title,
            'content' => $request->content,
            'image_url' => $imageUrl,
            'pewarta' => $request->pewarta,
            'editor' => $request->editor,
            'sumber' => $request->sumber,
            'copyright' => $request->copyright
        ]);

        return response()->json($article);
    }

    public function destroy($id) {
        Article::findOrFail($id)->delete();
        return response()->json(['message' => 'Article deleted']);
    }
}
