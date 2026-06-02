@extends('layouts.app')

@section('title', 'Dashboard — CineList')
@section('page-title', 'MY DASHBOARD')

@push('styles')
<style>
    .stat-card {
        background: #0d0d14;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 10px;
        padding: 22px 24px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
    }

    .stat-label {
        font-size: 10px;
        letter-spacing: 2px;
        color: rgba(255,255,255,0.3);
        margin-bottom: 6px;
    }

    .stat-value {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 38px;
        font-weight: 900;
        color: #fff;
        line-height: 1;
    }

    .stat-sub {
        font-size: 11px;
        color: rgba(255,255,255,0.25);
        margin-top: 4px;
    }

    .chart-card {
        background: #0d0d14;
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 10px;
        padding: 22px;
    }

    .chart-title {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 11px;
        letter-spacing: 3px;
        color: rgba(255,255,255,0.3);
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-title::before {
        content: '';
        width: 3px;
        height: 13px;
        background: #2979ff;
        border-radius: 2px;
    }

    .mini-row {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255,255,255,0.04);
    }

    .mini-row:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .mini-thumb {
        width: 30px;
        height: 40px;
        border-radius: 4px;
        background: #131320;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        flex-shrink: 0;
    }

    .mini-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .rank-num {
        font-family: 'Barlow Condensed', sans-serif;
        font-size: 20px;
        font-weight: 900;
        color: rgba(255,255,255,0.07);
        width: 24px;
        text-align: center;
        flex-shrink: 0;
    }

    .date-badge {
        background: rgba(41,121,255,0.08);
        border: 1px solid rgba(41,121,255,0.15);
        border-radius: 6px;
        padding: 4px 12px;
        font-size: 11px;
        color: rgba(255,255,255,0.35);
    }
</style>
@endpush

@section('content')

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
    <div style="font-size:13px;color:rgba(255,255,255,0.4);">
        Overview of your personal movie collection.
    </div>
    <div class="date-badge">
        <i class="bi bi-calendar3" style="margin-right:5px;"></i>
        {{ now()->format('M d, Y') }}
    </div>
</div>

{{-- STAT CARDS --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:16px;margin-bottom:24px;">
    
    <div class="stat-card">
        <div>
            <div class="stat-label">MY MOVIES</div>
            <div class="stat-value">{{ $totalMovies }}</div>
            <div class="stat-sub">All time</div>
        </div>
        <div class="stat-icon" style="background:rgba(41,121,255,0.1);color:#2979ff;">
            <i class="bi bi-film"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-label">GENRES</div>
            <div class="stat-value">{{ $totalGenres }}</div>
            <div class="stat-sub">Unique genres</div>
        </div>
        <div class="stat-icon" style="background:rgba(100,200,120,0.1);color:#3ecf6a;">
            <i class="bi bi-collection-play-fill"></i>
        </div>
    </div>

    <div class="stat-card">
        <div>
            <div class="stat-label">AVERAGE RATING</div>
            <div class="stat-value">
                {{ $avgRating > 0 ? number_format($avgRating, 2) : '—' }}
            </div>
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
                hoverOffset: 6
            }]
        }
    });
</script>
@endpush