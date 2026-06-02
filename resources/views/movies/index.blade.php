@extends('layouts.app')

@section('title', 'Movies — CineList')
@section('page-title', 'MY MOVIE LIST')

@push('styles')
<style>
    .hero-section { position:relative; height:300px; overflow:hidden; margin:-32px -32px 28px; }
    .hero-bg { position:absolute; inset:0; background:linear-gradient(135deg,#0f1535 0%,#1d1045 60%,#090915 100%); }
    .hero-overlay { position:absolute; inset:0; background:linear-gradient(90deg,rgba(0,0,0,0.85) 0%,rgba(0,0,0,0.2) 100%); }
    .hero-content { position:relative; z-index:2; padding:40px 36px; height:100%; display:flex; flex-direction:column; justify-content:flex-end; }
    .hero-label { font-size:9px; letter-spacing:4px; color:#2979ff; margin-bottom:8px; }
    .hero-title { font-family:'Barlow Condensed',sans-serif; font-weight:900; font-size:48px; line-height:1; color:#fff; text-transform:uppercase; letter-spacing:-1px; }
    .hero-badges { display:flex; gap:7px; margin-top:10px; flex-wrap:wrap; }
    .hero-badge { font-size:9px; padding:2px 10px; border-radius:20px; border:1px solid rgba(255,255,255,0.12); color:rgba(255,255,255,0.5); }
    .hero-badge.blue { background:rgba(41,121,255,0.15); border-color:rgba(41,121,255,0.35); color:#5c9fff; }
    .hero-director { font-size:11px; color:rgba(255,255,255,0.35); margin-top:7px; }
    .hero-rating { position:absolute; right:36px; bottom:32px; z-index:2; text-align:right; }
    .rating-big { font-family:'Barlow Condensed',sans-serif; font-size:56px; font-weight:900; color:#fff; line-height:1; }
    .rating-big span { font-size:16px; color:rgba(255,255,255,0.3); }
    .hero-poster { position:absolute; right:130px; bottom:0; z-index:1; }
    .hero-poster img { width:110px; height:160px; object-fit:cover; border-radius:7px 7px 0 0; border:1px solid rgba(255,255,255,0.08); opacity:.8; }
    .hero-poster-placeholder { width:110px; height:160px; background:#131325; border-radius:7px 7px 0 0; border:1px solid rgba(255,255,255,0.06); display:flex; align-items:center; justify-content:center; }
    .movie-thumb { width:36px; height:46px; border-radius:5px; background:#131320; display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0; }
    .movie-thumb img { width:100%; height:100%; object-fit:cover; }
    .featured-dot { width:6px; height:6px; border-radius:50%; background:#2979ff; display:inline-block; margin-right:5px; vertical-align:middle; }
</style>
@endpush

@section('content')

{{-- HERO --}}
@php $featured = $movies->firstWhere('is_featured', true) ?? $movies->first(); @endphp
@if($featured)
<div class="hero-section">
    <div class="hero-bg">
        @if($featured->backdrop)
            <img src="{{ asset('storage/'.$featured->backdrop) }}" style="width:100%;height:100%;object-fit:cover;opacity:.25;position:absolute;inset:0;">
        @endif
    </div>
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-label">{{ $featured->is_featured ? 'FEATURED FILM' : 'LATEST ADDITION' }}</div>
        <div class="hero-title">{{ Str::limit($featured->title, 28) }}</div>
        <div class="hero-badges">
            @if($featured->release_year)<span class="hero-badge blue">{{ $featured->release_year }}</span>@endif
            @foreach(explode(',', $featured->genre) as $g)
                <span class="hero-badge">{{ trim($g) }}</span>
            @endforeach
            @if($featured->duration)<span class="hero-badge">{{ $featured->duration }}</span>@endif
        </div>
        <div class="hero-director">Dir. {{ $featured->director }}</div>
    </div>
    <div class="hero-poster">
        @if($featured->poster)
            <img src="{{ asset('storage/'.$featured->poster) }}" alt="{{ $featured->title }}">
        @else
            <div class="hero-poster-placeholder">
                <i class="bi bi-film" style="font-size:24px;color:rgba(255,255,255,0.08);"></i>
            </div>
        @endif
    </div>
    @if($featured->rating)
    <div class="hero-rating">
        <div style="color:#2979ff;font-size:12px;margin-bottom:2px;"><i class="bi bi-star-fill"></i></div>
        <div class="rating-big">{{ number_format($featured->rating,1) }}<span>/10</span></div>
    </div>
    @endif
</div>
@endif

{{-- MOVIE LIST TABLE --}}
<div class="dark-card">
    <div style="padding:16px 20px;border-bottom:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:space-between;">
        <div class="section-label" style="margin-bottom:0;">MOVIE LIST</div>
        <a href="{{ route('movies.create') }}" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Add Movie
        </a>
    </div>

    @if($movies->count())
    <table class="dark-table">
        <thead>
            <tr>
                <th>#</th>
                <th>TITLE</th>
                <th>DIRECTOR</th>
                <th>GENRE</th>
                <th>YEAR</th>
                <th>RATING</th>
                <th>ACTIONS</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movies as $i => $movie)
            <tr>
                <td style="color:rgba(255,255,255,0.2);font-size:12px;">{{ $movies->firstItem() + $i }}</td>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div class="movie-thumb">
                            @if($movie->poster)
                                <img src="{{ asset('storage/'.$movie->poster) }}" alt="{{ $movie->title }}">
                            @else
                                <i class="bi bi-film" style="font-size:13px;color:rgba(255,255,255,0.1);"></i>
                            @endif
                        </div>
                        <div>
                            <div style="color:#fff;font-weight:500;font-size:13px;">
                                @if($movie->is_featured)
                                    <span class="featured-dot"></span>
                                @endif
                                {{ $movie->title }}
                            </div>
                            @if($movie->duration)
                            <div style="font-size:11px;color:rgba(255,255,255,0.25);">{{ $movie->duration }}</div>
                            @endif
                        </div>
                    </div>
                </td>
                <td style="font-size:12px;color:rgba(255,255,255,0.45);">{{ Str::limit($movie->director, 28) }}</td>
                <td>
                    @foreach(array_slice(explode(',', $movie->genre), 0, 2) as $g)
                        <span class="genre-pill">{{ trim($g) }}</span>
                    @endforeach
                </td>
                <td style="font-size:12px;color:rgba(255,255,255,0.45);">{{ $movie->release_year ?? '—' }}</td>
                <td>
                    <span class="rating-val">
                        <i class="bi bi-star-fill" style="font-size:10px;"></i>
                        {{ number_format($movie->rating, 1) }}
                    </span>
                </td>
                <td>
                    <div style="display:flex;gap:5px;">
                        <a href="{{ route('movies.show', $movie) }}" class="btn-icon" title="View">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('movies.edit', $movie) }}" class="btn-icon" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button class="btn-icon danger" title="Delete"
                            onclick="confirmDelete({{ $movie->id }}, '{{ addslashes($movie->title) }}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PAGINATION --}}
    @if($movies->hasPages())
    <div style="padding:12px 20px;border-top:1px solid rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:space-between;">
        <span style="font-size:11px;color:rgba(255,255,255,0.25);">
            Showing {{ $movies->firstItem() }} to {{ $movies->lastItem() }} of {{ $movies->total() }} movies
        </span>
        <div style="display:flex;gap:4px;">
            @if($movies->onFirstPage())
                <span class="btn-icon" style="opacity:.3;cursor:default;"><i class="bi bi-chevron-left"></i></span>
            @else
                <a href="{{ $movies->previousPageUrl() }}" class="btn-icon"><i class="bi bi-chevron-left"></i></a>
            @endif
            @foreach($movies->getUrlRange(1, $movies->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="btn-icon"
                    style="{{ $movies->currentPage() == $page ? 'background:#2979ff;color:#fff;border-color:#2979ff;' : '' }}">
                    {{ $page }}
                </a>
            @endforeach
            @if($movies->hasMorePages())
                <a href="{{ $movies->nextPageUrl() }}" class="btn-icon"><i class="bi bi-chevron-right"></i></a>
            @else
                <span class="btn-icon" style="opacity:.3;cursor:default;"><i class="bi bi-chevron-right"></i></span>
            @endif
        </div>
    </div>
    @endif

    @else
    <div style="padding:60px;text-align:center;">
        <i class="bi bi-film" style="font-size:40px;color:rgba(255,255,255,0.08);display:block;margin-bottom:16px;"></i>
        <div style="font-size:14px;color:rgba(255,255,255,0.3);margin-bottom:16px;">No movies in your list yet.</div>
        <a href="{{ route('movies.create') }}" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Add your first movie
        </a>
    </div>
    @endif
</div>

{{-- DELETE CONFIRMATION MODAL --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#0d0d14;border:1px solid rgba(255,255,255,0.08);border-radius:12px;">

            <div class="modal-header" style="border-bottom:1px solid rgba(255,255,255,0.06);padding:18px 22px;">
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;border-radius:8px;background:rgba(220,50,50,0.12);border:1px solid rgba(220,50,50,0.2);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-trash" style="color:#e05555;font-size:15px;"></i>
                    </div>
                    <h5 class="modal-title" style="font-family:'Barlow Condensed',sans-serif;font-size:18px;font-weight:700;color:#fff;margin:0;letter-spacing:.5px;">
                        Delete Movie
                    </h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" style="opacity:.4;"></button>
            </div>

            <div class="modal-body" style="padding:22px;">
                <p style="font-size:13px;color:rgba(255,255,255,0.5);margin:0;line-height:1.7;">
                    Are you sure you want to delete
                    <strong style="color:#fff;" id="modalMovieTitle"></strong>?
                    <br>
                    <span style="font-size:12px;color:rgba(220,80,80,0.7);margin-top:6px;display:block;">
                        <i class="bi bi-exclamation-triangle" style="margin-right:4px;"></i>
                        This action cannot be undone.
                    </span>
                </p>
            </div>

            <div class="modal-footer" style="border-top:1px solid rgba(255,255,255,0.06);padding:16px 22px;gap:8px;justify-content:flex-end;">
                <button type="button" data-bs-dismiss="modal"
                    style="padding:8px 18px;border-radius:7px;border:1px solid rgba(255,255,255,0.1);color:rgba(255,255,255,0.5);font-size:13px;background:none;cursor:pointer;font-family:'Barlow',sans-serif;">
                    Cancel
                </button>
                <form id="deleteForm" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        style="padding:8px 18px;border-radius:7px;border:none;background:#e05555;color:#fff;font-size:13px;font-weight:600;cursor:pointer;font-family:'Barlow',sans-serif;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-trash"></i> Yes, Delete
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function confirmDelete(id, title) {
    document.getElementById('modalMovieTitle').textContent = '"' + title + '"';
    document.getElementById('deleteForm').action = '/movies/' + id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush