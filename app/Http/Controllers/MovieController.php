<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Dompdf\Dompdf;

class MovieController extends Controller
{
    /**
     * Display dashboard with movies and genres
     */
    public function index(Request $request)
    {
        $query = Movie::with('genre');

        // Search Logic
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('director', 'like', "%{$searchTerm}%");
            });
        }

        // Genre Filtering
        if ($request->filled('genre_filter') && $request->genre_filter != '') {
            $query->where('genre_id', $request->genre_filter);
        }

        $movies = $query->latest()->get();
        $genres = Genre::all();
        $totalMovies = Movie::count();

        return view('dashboard', compact('movies', 'genres', 'totalMovies'));
    }

    /**
     * Store a new movie
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'nullable|exists:genres,id',
            'release_year' => 'nullable|integer',
            'rating' => 'nullable|numeric|min:0|max:10',
            'director' => 'nullable|string|max:255',
            'synopsis' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('movie-posters', 'public');
            $validated['photo'] = $photoPath;
        }

        Movie::create($validated);

        return back()->with('success', 'Movie added successfully!');
    }

    /**
     * Update an existing movie
     */
    public function update(Request $request, Movie $movie)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'genre_id' => 'nullable|exists:genres,id',
            'release_year' => 'nullable|integer',
            'rating' => 'nullable|numeric|min:0|max:10',
            'director' => 'nullable|string|max:255',
            'synopsis' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($movie->photo) {
                Storage::disk('public')->delete($movie->photo);
            }
            $photoPath = $request->file('photo')->store('movie-posters', 'public');
            $validated['photo'] = $photoPath;
        }

        $movie->update($validated);

        return back()->with('success', 'Movie updated successfully!');
    }

    /**
     * Move movie to trash (Soft Delete)
     */
    public function destroy(Movie $movie)
    {
        $movie->delete();
        return back()->with('success', 'Movie moved to trash.');
    }

    /**
     * Display trashed movies
     */
    public function trash()
    {
        $movies = Movie::onlyTrashed()->with('genre')->latest('deleted_at')->get();
        return view('trash', compact('movies'));
    }

    /**
     * Restore a trashed movie
     */
    public function restore($id)
    {
        $movie = Movie::withTrashed()->findOrFail($id);
        $movie->restore();
        return redirect()->route('movies.trash')->with('success', 'Movie restored successfully.');
    }

    /**
     * Permanently delete a movie
     */
    public function forceDelete($id)
    {
        $movie = Movie::withTrashed()->findOrFail($id);

        if ($movie->photo) {
            Storage::disk('public')->delete($movie->photo);
        }

        $movie->forceDelete();
        return redirect()->route('movies.trash')->with('success', 'Movie permanently deleted.');
    }

    /**
     * Export Movies to PDF
     */
    public function export(Request $request)
{
    $query = Movie::with('genre');

    // Search filter
    if ($request->filled('search')) {
        $searchTerm = $request->search;
        $query->where(function ($q) use ($searchTerm) {
            $q->where('title', 'like', "%{$searchTerm}%")
              ->orWhere('director', 'like', "%{$searchTerm}%");
        });
    }

    // Genre filter
    if ($request->filled('genre_filter')) {
        $query->where('genre_id', $request->genre_filter);
    }

    $movies = $query->latest()->get();
    $filename = 'movies_export_' . now()->format('Y-m-d') . '.pdf';

    $html = '<!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <title>Movies Export</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                padding: 20px;
                background-color: #f5f5f5;
            }
            .container {
                max-width: 1200px;
                margin: 0 auto;
                background-color: white;
                padding: 30px;
                box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            }
            h1 {
                color: #333;
                text-align: center;
                margin-bottom: 10px;
            }
            .export-info {
                text-align: center;
                color: #666;
                margin-bottom: 30px;
                font-size: 14px;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th {
                background-color: #2563eb;
                color: white;
                padding: 10px;
                text-align: left;
                border: 1px solid #1e40af;
            }
            td {
                padding: 8px 10px;
                border: 1px solid #ddd;
                font-size: 12px;
            }
            tr:nth-child(even) {
                background-color: #f9f9f9;
            }
            .footer {
                margin-top: 20px;
                padding: 12px;
                background-color: #f0f0f0;
                text-align: center;
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>🎬 Movie Vault – Export Report</h1>

            <div class="export-info">
                Exported on: ' . now()->format('F d, Y \a\t h:i A') . '<br>
                Total Movies: ' . $movies->count() . '
            </div>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Director</th>
                        <th>Release Year</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>';

    $number = 1;
    foreach ($movies as $movie) {
        $html .= '<tr>
            <td>' . $number++ . '</td>
            <td>' . htmlspecialchars($movie->title) . '</td>
            <td>' . htmlspecialchars($movie->genre->name ?? 'N/A') . '</td>
            <td>' . htmlspecialchars($movie->director ?? '-') . '</td>
            <td>' . ($movie->release_year ?? '-') . '</td>
            <td>' . ($movie->rating ?? '-') . '</td>
        </tr>';
    }

    $html .= '</tbody>
            </table>

            <div class="footer">
                Total Movies Exported: ' . $movies->count() . '
            </div>
        </div>
    </body>
    </html>';

    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();

    return $dompdf->stream($filename, ['Attachment' => true]);
    }
}
