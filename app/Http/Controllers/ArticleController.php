<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    /**
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getArticles(Request $request)
    {
        $query = Article::query();

        // Apply search query
        if ($request->has('search')) {
            $query->where(function ($subQuery) use ($request) {
                $searchTerm = $request->input('search');
                $subQuery->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhere('content', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by category
        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        // Filter by source
        if ($request->has('source')) {
            $query->where('source', $request->input('source'));
        }

        // Filter by date range
        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('published_at', [
                $request->input('date_from'),
                $request->input('date_to'),
            ]);
        }

        // Filter by user preferences: sources
        if ($request->has('sources')) {
            $sources = explode(',', $request->input('sources')); // Convert the string into an array
            $query->whereIn('source', $sources); // Pass the array to whereIn
        }




        // Filter by user preferences: categories
        if ($request->has('categories')) {
            $categories = explode(',', $request->input('categories')); // Convert the string into an array
            $query->whereIn('category', $categories); // Pass the array to whereIn
        }


        // Filter by user preferences: authors
        if ($request->has('authors')) {
            $authors = explode(',', $request->input('authors')); // Convert the string into an array
            $query->whereIn('author', $authors); // Pass the array to whereIn
        }





        // Retrieve matching articles
        $articles = $query->get();

        return response()->json($articles);
    }
}
