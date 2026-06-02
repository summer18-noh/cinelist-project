<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            return $this->adminDashboard();
        }
        return $this->userDashboard();
    }

    // ===========================
    // ADMIN DASHBOARD
    // ===========================
    private function adminDashboard()
    {
        // --- STAT CARDS ---
        $totalMovies  = Movie::count();
        $totalUsers   = User::count();
        $totalAdmins  = User::where('role', 'admin')->count();

        $rawAvg       = Movie::avg('rating');
        $avgRating    = $rawAvg ? round(floatval($rawAvg), 2) : 0;

        // --- ALL GENRES ---
        $totalGenres = 0;
        $genreCounts = collect([]);
        $allMovies   = Movie::pluck('genre');

        if ($allMovies->count()) {
            $allGenres   = $allMovies->flatMap(function ($g) {
                return array_map('trim', explode(',', $g));
            })->filter();
            $totalGenres = $allGenres->unique()->count();
            $genreCounts = $allGenres->countBy()->sortDesc()->take(6);
        }

        // --- RATING DISTRIBUTION ---
        $ratingRanges = [
            '1-2'  => intval(Movie::whereBetween('rating', [1,   2.9])->count()),
            '3-4'  => intval(Movie::whereBetween('rating', [3,   4.9])->count()),
            '5-6'  => intval(Movie::whereBetween('rating', [5,   6.9])->count()),
            '7-8'  => intval(Movie::whereBetween('rating', [7,   8.9])->count()),
            '9-10' => intval(Movie::whereBetween('rating', [9,  10  ])->count()),
        ];

        // --- MOVIES OVER TIME ---
        $moviesOverTime = collect([]);
        try {
            $moviesOverTime = Movie::select(
                    DB::raw('MONTHNAME(created_at) as month'),
                    DB::raw('MONTH(created_at) as month_num'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('created_at', now()->year)
                ->groupBy('month', 'month_num')
                ->orderBy('month_num')
                ->get()
                ->map(function ($item) {
                    $item->count = intval($item->count);
                    return $item;
                });
        } catch (\Exception $e) {
            $moviesOverTime = collect([]);
        }

        // --- RECENT USERS ---
        $recentUsers = User::latest()->take(5)->get();

        // --- TOP RATED (all users) ---
        $topRated = Movie::with('user')->orderByDesc('rating')->take(5)->get();

        // --- MOVIES PER USER ---
        $moviesPerUser = User::withCount('movies')
                             ->orderByDesc('movies_count')
                             ->take(5)
                             ->get();

        // --- RECENT MOVIES (all) ---
        $recentMovies = Movie::with('user')->latest()->take(5)->get();

        return view('dashboard.admin', compact(
            'totalMovies', 'totalUsers', 'totalAdmins', 'avgRating',
            'totalGenres', 'genreCounts', 'ratingRanges', 'moviesOverTime',
            'recentUsers', 'topRated', 'moviesPerUser', 'recentMovies'
        ));
    }

    // ===========================
    // USER DASHBOARD
    // ===========================
    private function userDashboard()
    {
        $userId = Auth::id();

        // --- STAT CARDS ---
        $totalMovies = Movie::where('user_id', $userId)->count();

        $rawAvg    = Movie::where('user_id', $userId)->avg('rating');
        $avgRating = $rawAvg ? round(floatval($rawAvg), 2) : 0;

        $thisMonth = Movie::where('user_id', $userId)
                          ->whereMonth('created_at', now()->month)
                          ->whereYear('created_at', now()->year)
                          ->count();

        // --- GENRES ---
        $totalGenres = 0;
        $genreCounts = collect([]);
        $allMovies   = Movie::where('user_id', $userId)->pluck('genre');

        if ($allMovies->count()) {
            $allGenres   = $allMovies->flatMap(function ($g) {
                return array_map('trim', explode(',', $g));
            })->filter();
            $totalGenres = $allGenres->unique()->count();
            $genreCounts = $allGenres->countBy()->sortDesc()->take(6);
        }

        // --- RATING DISTRIBUTION ---
        $ratingRanges = [
            '1-2'  => intval(Movie::where('user_id', $userId)->whereBetween('rating', [1,   2.9])->count()),
            '3-4'  => intval(Movie::where('user_id', $userId)->whereBetween('rating', [3,   4.9])->count()),
            '5-6'  => intval(Movie::where('user_id', $userId)->whereBetween('rating', [5,   6.9])->count()),
            '7-8'  => intval(Movie::where('user_id', $userId)->whereBetween('rating', [7,   8.9])->count()),
            '9-10' => intval(Movie::where('user_id', $userId)->whereBetween('rating', [9,  10  ])->count()),
        ];

        // --- MOVIES OVER TIME ---
        $moviesOverTime = collect([]);
        try {
            $moviesOverTime = Movie::where('user_id', $userId)
                ->select(
                    DB::raw('MONTHNAME(created_at) as month'),
                    DB::raw('MONTH(created_at) as month_num'),
                    DB::raw('COUNT(*) as count')
                )
                ->whereYear('created_at', now()->year)
                ->groupBy('month', 'month_num')
                ->orderBy('month_num')
                ->get()
                ->map(function ($item) {
                    $item->count = intval($item->count);
                    return $item;
                });
        } catch (\Exception $e) {
            $moviesOverTime = collect([]);
        }

        // --- RECENT + TOP RATED ---
        $recentMovies = Movie::where('user_id', $userId)->latest()->take(5)->get();
        $topRated     = Movie::where('user_id', $userId)->orderByDesc('rating')->take(5)->get();

        return view('dashboard.user', compact(
            'totalMovies', 'avgRating', 'thisMonth', 'totalGenres',
            'genreCounts', 'ratingRanges', 'moviesOverTime',
            'recentMovies', 'topRated'
        ));
    }
}