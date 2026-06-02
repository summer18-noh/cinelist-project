<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Movie;

class MovieSeeder extends Seeder
{
    public function run(): void
{
    $user = \App\Models\User::first();

    if (!$user) {
        $user = \App\Models\User::create([
            'name'     => 'Juan Dela Cruz',
            'email'    => 'admin@movielist.com',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
            'role'     => 'admin',
        ]);
    }

    $movies = [
        [
            'title'        => 'Normal People',
            'director'     => 'Lenny Abrahamson, Hettie Mcdonald',
            'description'  => 'Follows Connell and Marianne as they grow up, weaving in and out of each other\'s lives in a tender but complicated love story.',
            'cast'         => 'Daisy Edgar-Jones, Paul Mescal',
            'genre'        => 'Drama, Romance',
            'language'     => 'English',
            'release_year' => 2020,
            'duration'     => '5h 30m',
            'rating'       => 8.5,
            'votes'        => 2100,
            'is_featured'  => false,
            'user_id'      => $user->id,
        ],
        [
            'title'        => 'Call Me By Your Name',
            'director'     => 'Luca Guadagnino',
            'description'  => 'In the summer of 1983, seventeen-year-old Elio spends his days in his family\'s villa in Northern Italy.',
            'cast'         => 'Timothée Chalamet, Armie Hammer, Michael Stuhlbarg',
            'genre'        => 'Romance, Drama',
            'language'     => 'English',
            'release_year' => 2017,
            'duration'     => '2h 12m',
            'rating'       => 8.2,
            'votes'        => 1245,
            'is_featured'  => true,
            'user_id'      => $user->id,
        ],
        [
            'title'        => 'The Avengers',
            'director'     => 'Joss Whedon',
            'description'  => 'Earth\'s mightiest heroes must come together to stop Loki and his alien army from enslaving humanity.',
            'cast'         => 'Robert Downey Jr., Chris Evans, Scarlett Johansson',
            'genre'        => 'Action, Sci-Fi',
            'language'     => 'English',
            'release_year' => 2012,
            'duration'     => '2h 23m',
            'rating'       => 8.0,
            'votes'        => 5000,
            'is_featured'  => false,
            'user_id'      => $user->id,
        ],
    ];

    foreach ($movies as $movie) {
        \App\Models\Movie::create($movie);
    }
}
}