<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
    // ✅ Helper — i-check kung may-ari ng movie ang current user
    private function authorizeMovie(Movie $movie)
    {
        if ($movie->user_id !== Auth::id()) {
            abort(403, 'You do not have permission to access this movie.');
        }
    }

    public function index()
    {
        // ✅ Sariling movies lang ang makikita
        $movies = Movie::where('user_id', Auth::id())
                       ->latest()
                       ->paginate(10);

        return view('movies.index', compact('movies'));
    }

    public function create()
    {
        return view('movies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'director'     => 'required|string|max:255',
            'description'  => 'nullable|string',
            'cast'         => 'nullable|string',
            'genre'        => 'required|string',
            'language'     => 'nullable|string|max:100',
            'release_year' => 'nullable|integer|min:1888|max:' . (date('Y') + 2),
            'duration'     => 'nullable|string|max:20',
            'rating'       => 'nullable|numeric|min:0|max:10',
            'poster'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured'  => 'nullable|boolean',
        ]);

        $data                = $request->except(['poster', '_token']);
        $data['user_id']     = Auth::id(); // ✅ Auto-assign sa current user
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('poster')) {
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        Movie::create($data);

        return redirect()->route('movies.index')
            ->with('toast_success', '"' . $request->title . '" added to your list!');
    }

    public function show(Movie $movie)
    {
        // ✅ Hindi makikita ng iba kahit i-manual ang URL
        $this->authorizeMovie($movie);
        return view('movies.show', compact('movie'));
    }

    public function edit(Movie $movie)
    {
        // ✅ Hindi ma-eeedit ng iba
        $this->authorizeMovie($movie);
        return view('movies.edit', compact('movie'));
    }

    public function update(Request $request, Movie $movie)
    {
        // ✅ Hindi ma-uupdate ng iba
        $this->authorizeMovie($movie);

        $request->validate([
            'title'        => 'required|string|max:255',
            'director'     => 'required|string|max:255',
            'description'  => 'nullable|string',
            'cast'         => 'nullable|string',
            'genre'        => 'required|string',
            'language'     => 'nullable|string|max:100',
            'release_year' => 'nullable|integer|min:1888|max:' . (date('Y') + 2),
            'duration'     => 'nullable|string|max:20',
            'rating'       => 'nullable|numeric|min:0|max:10',
            'votes'        => 'nullable|integer|min:0',
            'poster'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured'  => 'nullable|boolean',
        ]);

        $data                = $request->except(['poster', '_token', '_method']);
        $data['is_featured'] = $request->has('is_featured') ? 1 : 0;

        if ($request->hasFile('poster')) {
            if ($movie->poster) {
                Storage::disk('public')->delete($movie->poster);
            }
            $data['poster'] = $request->file('poster')->store('posters', 'public');
        }

        $movie->update($data);

        return redirect()->route('movies.index')
            ->with('toast_success', '"' . $movie->title . '" updated successfully!');
    }

    public function destroy(Movie $movie)
    {
        // ✅ Hindi ma-dedelete ng iba
        $this->authorizeMovie($movie);

        if ($movie->poster) {
            Storage::disk('public')->delete($movie->poster);
        }

        $title = $movie->title;
        $movie->delete();

        return redirect()->route('movies.index')
            ->with('toast_error', '"' . $title . '" has been deleted.');
    }
}