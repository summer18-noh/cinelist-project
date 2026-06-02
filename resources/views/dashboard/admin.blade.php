@extends('layouts.app')

@section('title', 'Admin Dashboard — CineList')
@section('page-title', 'ADMIN DASHBOARD')

@push('styles')
<style>
    .stat-card { background:#0d0d14; border:1px solid rgba(255,255,255,0.06); border-radius:10px; padding:22px 24px; display:flex; align-items:center; justify-content:space-between; }
    .stat-icon { width:52px; height:52px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:22px; }
    .stat-label { font-size:10px; letter-spacing:2px; color:rgba(255,255,255,0.3); margin-bottom:6px; }
    .stat-value { font-family:'Barlow Condensed',sans-serif; font-size:38px; font-weight:900; color:#fff; line-height:1; }
    .stat-sub { font-size:11px; color:rgba(255,255,255,0.25); margin-top:4px; }
    .chart-card { background:#0d0d14; border:1px solid rgba(255,255,255,0.06); border-radius:10px; padding:22px; }
    .chart-title { font-family:'Barlow Condensed',sans-serif; font-size:11px; letter-spacing:3px; color:rgba(255,255,255,0.3); margin-bottom:20px; display:flex; align-items:center; gap:8px; }
    .chart-title::before { content:''; width:3px; height:13px; background:#2979ff; border-radius:2px; }
    .mini-row { display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.04); }
    .mini-row:last-child { border-bottom:none; padding-bottom:0; }
    .mini-av { width:32px; height:32px; border-radius:50%; background:#2979ff; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600; flex-shrink:0; overflow:hidden; }
    .mini-av img { width:100%; height:100%; object-fit:cover; }
    .mini-thumb { width:30px; height:40px; border-radius:4px; background:#131320; display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0; }
    .mini-thumb img { width:100%; height:100%; object-fit:cover; }
    .date-badge { background:rgba(41,121,255,0.08); border:1px solid rgba(41,121,255,0.15); border-radius:6px; padding:4px 12px; font-size:11px; color:rgba(255,255,255,0.35); }
    .rank-num { font-family:'Barlow Condensed',sans-serif; font-size:20px; font-weight:900; color:rgba(255,255,255,0.07); width:24px; text-align:center; flex-shrink:0; }
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div style="font-size:13px;color:rgba(255,255,255,0.4);">
        System-wide overview of all users and movies.
    </div>
    <div class="date-badge"><i class="bi bi-calendar3" style="margin-right:5px;"></i>{{ now()->format('M d, Y') }}</div>
</div>

{{-- STAT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <div class="stat-card">
        <div>
            <div class="stat-label">TOTAL MOVIES</div>
            <div class="stat-value">{{ $totalMovies }}</div>
            <div class="stat-sub">All users combined</div>
        </div>
        <div class="stat-icon" style="background:rgba(41,121,255,0.1);color:#2979ff;">
            <i class="bi bi-film"></i>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">TOTAL USERS</div>
            <div class="stat-value">{{ $totalUsers }}</div>
            <div class="stat-sub">{{ $totalAdmins }} admin{{ $totalAdmins > 1 ? 's' : '' }}</div>
        </div>
        <div class="stat-icon" style="background:rgba(100,200,120,0.1);color:#3ecf6a;">
            <i class="bi bi-people"></i>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">AVERAGE RATING</div>
            <div class="stat-value">{{ $avgRating > 0 ? number_format($avgRating, 2) : '—' }}</div>
            <div class="stat-sub">Across all movies</div>
        </div>
        <div class="stat-icon" style="background:rgba(255,180,0,0.1);color:#ffb400;">
            <i class="bi bi-star"></i>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">TOTAL GENRES</div>
            <div class="stat-value">{{ $totalGenres }}</div>
            <div class="stat-sub">Unique genres</div>
        </div>
        <div class="stat-icon" style="background:rgba(220,60,60,0.1);color:#e05555;">
            <i class="bi bi-collection-play-fill"></i>
        </div>
    </div>
</div>

{{-- CHARTS ROW --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">

    {{-- DONUT: Genre --}}
    <div class="chart-card">
        <div class="chart-title">MOVIES BY GENRE</div>
        <div style="position:relative;height:180px;display:flex;align-items:center;justify-content:center;">
            <canvas id="genreChart"></canvas>
        </div>
        <div style="margin-top:14px;display:flex;flex-direction:column;gap:6px;" id="genreLegend"></div>
    </div>

    {{-- BAR: Rating --}}
    <div class="chart-card">
        <div class="chart-title">RATING DISTRIBUTION</div>
        <div style="position:relative;height:220px;">
            <canvas id="ratingChart"></canvas>
        </div>
    </div>

    {{-- LINE: Over Time --}}
    <div class="chart-card">
        <div class="chart-title">MOVIES OVER TIME</div>
        <div style="position:relative;height:220px;">
            <canvas id="timeChart"></canvas>
        </div>
    </div>
</div>

{{-- BOTTOM ROW --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">

    {{-- RECENT USERS --}}
    <div class="chart-card">
        <div class="chart-title">RECENT USERS</div>
        @forelse($recentUsers as $user)
        <div class="mini-row">
            <div class="mini-av">
                @if($user->profile_image)
                    <img src="{{ asset('storage/'.$user->profile_image) }}">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:12px;color:rgba(255,255,255,0.8);font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</div>
                <div style="font-size:10px;color:rgba(255,255,255,0.28);">{{ $user->email }}</div>
            </div>
            <span style="font-size:9px;padding:2px 8px;border-radius:20px;letter-spacing:1px;{{ $user->role === 'admin' ? 'background:rgba(41,121,255,0.12);border:1px solid rgba(41,121,255,0.25);color:#5c9fff;' : 'background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.35);' }}">
                {{ strtoupper($user->role) }}
            </span>
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:rgba(255,255,255,0.2);font-size:13px;">No users yet.</div>
        @endforelse
    </div>

    {{-- TOP RATED --}}
    <div class="chart-card">
        <div class="chart-title">TOP RATED MOVIES</div>
        @forelse($topRated as $i => $movie)
        <div class="mini-row">
            <div class="rank-num">{{ $i + 1 }}</div>
            <div class="mini-thumb">
                @if($movie->poster)
                    <img src="{{ asset('storage/'.$movie->poster) }}">
                @else
                    <i class="bi bi-film" style="font-size:11px;color:rgba(255,255,255,0.1);"></i>
                @endif
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:12px;color:rgba(255,255,255,0.8);font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $movie->title }}</div>
                <div style="font-size:10px;color:rgba(255,255,255,0.28);">by {{ $movie->user->name ?? 'Unknown' }}</div>
            </div>
            <span style="font-size:12px;color:#2979ff;font-weight:600;">
                <i class="bi bi-star-fill" style="font-size:10px;"></i> {{ number_format($movie->rating,1) }}
            </span>
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:rgba(255,255,255,0.2);font-size:13px;">No movies yet.</div>
        @endforelse
    </div>

    {{-- MOVIES PER USER --}}
    <div class="chart-card">
        <div class="chart-title">MOVIES PER USER</div>
        @forelse($moviesPerUser as $i => $user)
        <div class="mini-row">
            <div class="rank-num">{{ $i + 1 }}</div>
            <div class="mini-av">
                @if($user->profile_image)
                    <img src="{{ asset('storage/'.$user->profile_image) }}">
                @else
                    {{ strtoupper(substr($user->name, 0, 2)) }}
                @endif
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:12px;color:rgba(255,255,255,0.8);font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</div>
                <div style="font-size:10px;color:rgba(255,255,255,0.28);">{{ $user->movies_count }} {{ $user->movies_count == 1 ? 'movie' : 'movies' }}</div>
            </div>
            <div style="width:60px;background:rgba(255,255,255,0.05);border-radius:10px;height:5px;overflow:hidden;">
                @php $maxMovies = $moviesPerUser->max('movies_count'); @endphp
                <div style="height:100%;background:#2979ff;border-radius:10px;width:{{ $maxMovies > 0 ? ($user->movies_count / $maxMovies) * 100 : 0 }}%;"></div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:rgba(255,255,255,0.2);font-size:13px;">No data yet.</div>
        @endforelse
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.color = 'rgba(255,255,255,0.35)';
Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
Chart.defaults.font.family = "'Barlow', sans-serif";
Chart.defaults.font.size = 11;

const genreLabels = @json($genreCounts->keys()->values());
const genreData   = @json($genreCounts->values()->map(fn($v) => (int) $v)->values());
const genreColors = ['#2979ff','#3ecf6a','#9c6cff','#ff6b6b','#ffb400','#00c8ff'];

const genreCtx = document.getElementById('genreChart').getContext('2d');
new Chart(genreCtx, {
    type: 'doughnut',
    data: {
        labels: genreLabels,
        datasets: [{
            data: genreData,
            backgroundColor: genreColors,
            borderColor: '#0d0d14',
            borderWidth: 3,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#111118', borderColor: 'rgba(255,255,255,0.08)', borderWidth: 1, padding: 10 } }
    }
});

const legendEl = document.getElementById('genreLegend');
genreLabels.forEach((label, i) => {
    legendEl.innerHTML += `
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:7px;">
                <div style="width:8px;height:8px;border-radius:50%;background:${genreColors[i] ?? '#888'};flex-shrink:0;"></div>
                <span style="font-size:12px;color:rgba(255,255,255,0.5);">${label}</span>
            </div>
            <span style="font-size:12px;color:rgba(255,255,255,0.3);">${genreData[i]}</span>
        </div>`;
});

const ratingValues = @json(array_values($ratingRanges));
const ratingMax    = Math.max(...ratingValues.map(v => Number(v)));
const ratingCtx    = document.getElementById('ratingChart').getContext('2d');
new Chart(ratingCtx, {
    type: 'bar',
    data: {
        labels: @json(array_keys($ratingRanges)),
        datasets: [{
            label: 'Movies',
            data: ratingValues.map(v => Number(v)),
            backgroundColor: ratingValues.map(v => Number(v) === ratingMax ? '#2979ff' : 'rgba(41,121,255,0.3)'),
            borderRadius: 5,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#111118', borderColor: 'rgba(255,255,255,0.08)', borderWidth: 1, padding: 10 } },
        scales: {
            x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.3)' } },
            y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: 'rgba(255,255,255,0.3)', stepSize: 1, precision: 0 }, beginAtZero: true }
        }
    }
});

const timeCtx    = document.getElementById('timeChart').getContext('2d');
const timeLabels = @json($moviesOverTime->pluck('month')->values());
const timeData   = @json($moviesOverTime->pluck('count')->map(fn($v) => (int) $v)->values());
const gradient   = timeCtx.createLinearGradient(0, 0, 0, 200);
gradient.addColorStop(0, 'rgba(41,121,255,0.25)');
gradient.addColorStop(1, 'rgba(41,121,255,0)');

new Chart(timeCtx, {
    type: 'line',
    data: {
        labels: timeLabels.length ? timeLabels : ['Jan','Feb','Mar','Apr','May','Jun'],
        datasets: [{
            label: 'Movies Added',
            data: timeData.length ? timeData : [0,0,0,0,0,0],
            borderColor: '#2979ff',
            backgroundColor: gradient,
            borderWidth: 2,
            pointBackgroundColor: '#2979ff',
            pointRadius: 4,
            pointHoverRadius: 6,
            tension: 0.4,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false }, tooltip: { backgroundColor: '#111118', borderColor: 'rgba(255,255,255,0.08)', borderWidth: 1, padding: 10 } },
        scales: {
            x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.3)' } },
            y: { grid: { color: 'rgba(255,255,255,0.04)' }, ticks: { color: 'rgba(255,255,255,0.3)', stepSize: 1, precision: 0 }, beginAtZero: true }
        }
    }
});
</script>
@endpush