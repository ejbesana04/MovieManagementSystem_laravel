<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $movies = Movie::with('genre')->get();
        $genres = Genre::all();

        return view('movies.index', compact('movies', 'genres'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'genre_id' => 'nullable|exists:genres,id',
            'release_year' => 'nullable|integer',
            'rating' => 'nullable|numeric',
            'director' => 'nullable|string',
            'synopsis' => 'nullable',
        ]);

        Movie::create($request->all());

        return back()->with('success', 'Movie added successfully!');
    }

    public function update(Request $request, Movie $movie)
    {
        $request->validate([
            'title' => 'required',
            'genre_id' => 'nullable|exists:genres,id',
            'release_year' => 'nullable|integer',
            'rating' => 'nullable|numeric',
            'director' => 'nullable|string',
            'synopsis' => 'nullable',
        ]);

        $movie->update($request->all());

        return back()->with('success', 'Movie updated successfully!');
    }

    public function destroy(Movie $movie)
    {
        $movie->delete();

        return back()->with('success', 'Movie deleted successfully!');
    }
}
