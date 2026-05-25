<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    public function index(Request $request)
    {
        $news = News::orderBy('created_at', 'desc')->paginate(10);

        if ($request->wantsJson()) {
            return response()->json($news);
        }

        return view('admin.news.index', compact('news'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|url',
            'is_published' => 'sometimes|boolean',
        ]);

        // галка опубликовать = сегодняшняя дата
        if (isset($validated['is_published']) && $validated['is_published']) {
            $validated['published_at'] = now();
        }

        $newsItem = News::create($validated);

        if ($request->wantsJson()) {
            return response()->json($newsItem, 201);
        }

        return redirect()->route('admin.news.index')->with('success', 'Новость создана!');
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|url',
            'is_published' => 'sometimes|boolean',
        ]);

        if (isset($validated['is_published']) && $validated['is_published'] && !$news->published_at) {
            $validated['published_at'] = now();
        } 
        elseif (!isset($validated['is_published'])) {
            $validated['is_published'] = false;
            $validated['published_at'] = null;
        }

        $news->update($validated);

        if ($request->wantsJson()) {
            return response()->json($news);
        }

        return redirect()->route('admin.news.index')->with('success', 'Новость обновлена!');
    }

    public function show(News $news)
    {
        if (request()->wantsJson()) {
            return response()->json($news);
        }
        return redirect()->route('admin.news.index');
    }

    public function destroy(News $news)
    {
        $news->delete();

        return redirect()->route('admin.news.index')->with('success', 'Новость удалена!');
    }

}