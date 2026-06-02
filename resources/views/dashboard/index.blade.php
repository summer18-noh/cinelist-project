@extends('layouts.app')

@section('title', 'Dashboard — CineList')
@section('page-title', 'DASHBOARD')

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
    .mini-movie { display:flex; align-items:center; gap:10px; padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.04); }
    .mini-movie:last-child { border-bottom:none; padding-bottom:0; }
    .mini-thumb { width:32px; height:42px; border-radius:4px; background:#131320; display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0; }
    .mini-thumb img { width:100%; height:100%; object-fit:cover; }
    .mini-thumb i { font-size:12px; color:rgba(255,255,255,0.1); }
    .date-badge { background:rgba(41,121,255,0.08); border:1px solid rgba(41,121,255,0.15); border-radius:6px; padding:3px 10px; font-size:11px; color:rgba(255,255,255,0.35); }
</style>
@endpush

@section('content')

{{-- DATE BADGE --}}
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div>
        <div style="font-size:13px;color:rgba(255,255,255,0.5);">Overview of your movie collection.</div>
    </div>
    <div class="date-badge"><i class="bi bi-calendar3" style="margin-right:5px;"></i>{{ now()->format('M d, Y') }}</div>
</div>

{{-- STAT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    <div class="stat-card">
        <div>
            <div class="stat-label">TOTAL MOVIES</div>
            <div class="stat-value">{{ $totalMovies }}</div>
            <div class="stat-sub">All time</div>
        </div>
        <div class="stat-icon" style="background:rgba(100,200,120,0.1);color:#3ecf6a;">
    <i class="bi bi-collection-play-fill"></i>
</div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">GENRES</div>
            <div class="stat-value">{{ $totalGenres }}</div>
            <div class="stat-sub">Unique genres</div>
        </div>
        <div class="stat-icon" style="background:rgba(100,200,120,0.1);color:#3ecf6a;">
    <img src="{{ asset('images/film-outline.svg') }}" 
         style="width:24px;height:24px;filter:invert(62%) sepia(50%) saturate(400%) hue-rotate(95deg) brightness(95%);">
</div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">AVERAGE RATING</div>
            <div class="stat-value">{{ $avgRating ? number_format($avgRating, 2) : '—' }}</div>
            <div class="stat-sub">Out of 10</div>
        </div>
        <div class="stat-icon" style="background:rgba(255,180,0,0.1);color:#ffb400;">
            <i class="bi bi-star"></i>
        </div>
    </div>
    <div class="stat-card">
        <div>
            <div class="stat-label">THIS MONTH</div>
            <div class="stat-value">{{ $thisMonth }}</div>
            <div class="stat-sub">New movies</div>
        </div>
        <div class="stat-icon" style="background:rgba(220,60,60,0.1);color:#e05555;">
            <i class="bi bi-calendar-plus"></i>
        </div>
    </div>
</div>

{{-- CHARTS ROW 1 --}}
<div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px;">

    {{-- DONUT: Movies by Genre --}}
    <div class="chart-card">
        <div class="chart-title">MOVIES BY GENRE</div>
        <div style="position:relative;height:180px;display:flex;align-items:center;justify-content:center;">
            <canvas id="genreChart"></canvas>
        </div>
        <div style="margin-top:14px;display:flex;flex-direction:column;gap:6px;" id="genreLegend"></div>
    </div>

    {{-- BAR: Rating Distribution --}}
    <div class="chart-card">
        <div class="chart-title">RATING DISTRIBUTION</div>
        <div style="position:relative;height:220px;">
            <canvas id="ratingChart"></canvas>
        </div>
    </div>

    {{-- LINE: Movies Over Time --}}
    <div class="chart-card">
        <div class="chart-title">MOVIES OVER TIME</div>
        <div style="position:relative;height:220px;">
            <canvas id="timeChart"></canvas>
        </div>
    </div>
</div>

{{-- BOTTOM ROW --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

    {{-- RECENT MOVIES --}}
    <div class="chart-card">
        <div class="chart-title">RECENTLY ADDED</div>
        @forelse($recentMovies as $movie)
        <div class="mini-movie">
            <div class="mini-thumb">
                @if($movie->poster)
                    <img src="{{ asset('storage/'.$movie->poster) }}" alt="{{ $movie->title }}">
                @else
                    <i class="bi bi-film"></i>
                @endif
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;color:rgba(255,255,255,0.8);font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $movie->title }}</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.28);">{{ $movie->release_year }} &nbsp;·&nbsp; {{ Str::limit($movie->genre, 20) }}</div>
            </div>
            <span style="font-size:12px;color:#2979ff;font-weight:600;"><i class="bi bi-star-fill" style="font-size:10px;"></i> {{ number_format($movie->rating,1) }}</span>
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:rgba(255,255,255,0.2);font-size:13px;">No movies yet.</div>
        @endforelse
    </div>

    {{-- TOP RATED --}}
    <div class="chart-card">
        <div class="chart-title">TOP RATED</div>
        @forelse($topRated as $i => $movie)
        <div class="mini-movie">
            <div style="font-family:'Barlow Condensed',sans-serif;font-size:22px;font-weight:900;color:rgba(255,255,255,0.08);width:28px;text-align:center;flex-shrink:0;">{{ $i + 1 }}</div>
            <div class="mini-thumb">
                @if($movie->poster)
                    <img src="{{ asset('storage/'.$movie->poster) }}" alt="{{ $movie->title }}">
                @else
                    <i class="bi bi-film"></i>
                @endif
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:13px;color:rgba(255,255,255,0.8);font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $movie->title }}</div>
                <div style="font-size:11px;color:rgba(255,255,255,0.28);">{{ $movie->director }}</div>
            </div>
            <div style="text-align:right;">
                <div style="font-family:'Barlow Condensed',sans-serif;font-size:20px;font-weight:700;color:#fff;">{{ number_format($movie->rating,1) }}</div>
                <div style="font-size:10px;color:rgba(255,255,255,0.25);">/ 10</div>
            </div>
        </div>
        @empty
        <div style="text-align:center;padding:24px;color:rgba(255,255,255,0.2);font-size:13px;">No movies yet.</div>
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

// --- GENRE DATA ---
const genreLabels = @json($genreCounts->keys());
const genreData   = @json($genreCounts->values());
const genreColors = ['#2979ff','#3ecf6a','#9c6cff','#ff6b6b','#ffb400','#00c8ff'];

// --- DONUT CHART ---
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
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#111118',
                borderColor: 'rgba(255,255,255,0.08)',
                borderWidth: 1,
                padding: 10,
                titleColor: '#fff',
                bodyColor: 'rgba(255,255,255,0.5)',
            }
        }
    }
});

// Build legend
const legendEl = document.getElementById('genreLegend');
genreLabels.forEach((label, i) => {
    legendEl.innerHTML += `
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div style="display:flex;align-items:center;gap:7px;">
                <div style="width:8px;height:8px;border-radius:50%;background:${genreColors[i]};flex-shrink:0;"></div>
                <span style="font-size:12px;color:rgba(255,255,255,0.5);">${label}</span>
            </div>
            <span style="font-size:12px;color:rgba(255,255,255,0.3);">${genreData[i]}</span>
        </div>`;
});

// --- BAR CHART: Rating Distribution ---
const ratingCtx = document.getElementById('ratingChart').getContext('2d');
new Chart(ratingCtx, {
    type: 'bar',
    data: {
        labels: @json(array_keys($ratingRanges)),
        datasets: [{
            label: 'Movies',
            data: @json(array_values($ratingRanges)),
            backgroundColor: (ctx) => {
                const val = ctx.raw;
                if (val === Math.max(...@json(array_values($ratingRanges)))) return '#2979ff';
                return 'rgba(41,121,255,0.3)';
            },
            borderRadius: 5,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#111118',
                borderColor: 'rgba(255,255,255,0.08)',
                borderWidth: 1,
                padding: 10,
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { color: 'rgba(255,255,255,0.3)' }
            },
            y: {
                grid: { color: 'rgba(255,255,255,0.04)' },
                ticks: {
                    color: 'rgba(255,255,255,0.3)',
                    stepSize: 1,
                    precision: 0,
                },
                beginAtZero: true,
            }
        }
    }
});

// --- LINE CHART: Movies Over Time ---
const timeCtx = document.getElementById('timeChart').getContext('2d');
const timeLabels = @json($moviesOverTime->pluck('month'));
const timeData   = @json($moviesOverTime->pluck('count'));

const gradient = timeCtx.createLinearGradient(0, 0, 0, 200);
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
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#111118',
                borderColor: 'rgba(255,255,255,0.08)',
                borderWidth: 1,
                padding: 10,
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { color: 'rgba(255,255,255,0.3)' }
            },
            y: {
                grid: { color: 'rgba(255,255,255,0.04)' },
                ticks: {
                    color: 'rgba(255,255,255,0.3)',
                    stepSize: 1,
                    precision: 0,
                },
                beginAtZero: true,
            }
        }
    }
});
</script>
@endpush