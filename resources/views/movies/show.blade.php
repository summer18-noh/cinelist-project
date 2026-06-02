@extends('layouts.app')

@section('title', $movie->title . ' — CineList')
@section('page-title', 'MOVIE DETAIL')

@section('content')

<div style="margin-bottom:20px;">
    <a href="{{ route('movies.index') }}" style="color:rgba(255,255,255,0.35);font-size:13px;text-decoration:none;display:inline-flex;align-items:center;gap:6px;">
        <i class="bi bi-arrow-left"></i> Back to Movies
    </a>
</div>

{{-- HERO --}}
<div style="position:relative;height:240px;border-radius:10px;overflow:hidden;margin-bottom:22px;background:linear-gradient(135deg,#0f1535 0%,#1d1045 60%,#090915 100%);">
    @if($movie->backdrop)
        <img src="{{ asset('storage/'.$movie->backdrop) }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;opacity:.25;">
    @endif
    <div style="position:absolute;inset:0;background:linear-gradient(90deg,rgba(0,0,0,0.85) 0%,rgba(0,0,0,0.1) 100%);"></div>
    <div style="position:relative;z-index:2;padding:32px;height:100%;display:flex;flex-direction:column;justify-content:flex-end;">
        @if($movie->is_featured)
        <div style="font-size:9px;letter-spacing:4px;color:#2979ff;margin-bottom:8px;">FEATURED FILM</div>
        @endif
        <div style="font-family:'Barlow Condensed',sans-serif;font-weight:900;font-size:44px;line-height:1;color:#fff;text-transform:uppercase;letter-spacing:-1px;">
            {{ $movie->title }}
        </div>
        <div style="font-size:12px;color:rgba(255,255,255,0.4);margin-top:8px;display:flex;gap:10px;flex-wrap:wrap;">
            @if($movie->release_year)<span>{{ $movie->release_year }}</span>@endif
            @if($movie->genre)<span>·</span><span>{{ $movie->genre }}</span>@endif
            @if($movie->duration)<span>·</span><span>{{ $movie->duration }}</span>@endif
        </div>
    </div>
    @if($movie->rating)
    <div style="position:absolute;right:32px;bottom:28px;z-index:2;text-align:right;">
        <div style="color:#2979ff;font-size:12px;margin-bottom:2px;"><i class="bi bi-star-fill"></i></div>
        <div style="font-family:'Barlow Condensed',sans-serif;font-size:50px;font-weight:900;color:#fff;line-height:1;">
            {{ number_format($movie->rating,1) }}<span style="font-size:16px;color:rgba(255,255,255,0.3);">/10</span>
        </div>
    </div>
    @endif
</div>

{{-- DETAIL BODY --}}
<div style="display:grid;grid-template-columns:150px 1fr;gap:18px;">

    {{-- POSTER --}}
    <div>
        @if($movie->poster)
            <img src="{{ asset('storage/'.$movie->poster) }}" style="width:100%;border-radius:8px;border:1px solid rgba(255,255,255,0.08);" alt="{{ $movie->title }}">
        @else
            <div style="width:100%;height:210px;background:#0d0d14;border-radius:8px;border:1px solid rgba(255,255,255,0.06);display:flex;align-items:center;justify-content:center;">
                <i class="bi bi-film" style="font-size:28px;color:rgba(255,255,255,0.08);"></i>
            </div>
        @endif

        {{-- ACTION BUTTONS --}}
        <div style="margin-top:10px;display:flex;gap:6px;">
            <a href="{{ route('movies.edit', $movie) }}" class="btn-primary-custom" style="flex:1;justify-content:center;font-size:12px;padding:7px;">
                <i class="bi bi-pencil"></i> Edit
            </a>
            <button class="btn-icon danger" style="width:34px;height:34px;" title="Delete"
                onclick="confirmDelete({{ $movie->id }}, '{{ addslashes($movie->title) }}')">
                <i class="bi bi-trash"></i>
            </button>
        </div>
    </div>

    {{-- INFO CARD --}}
    <div class="dark-card" style="padding:22px;">
        <div style="display:grid;grid-template-columns:1fr 0.5px 1fr;gap:0;">

            {{-- LEFT: OVERVIEW --}}
            <div style="padding-right:22px;">
                <div class="section-label" style="margin-bottom:14px;">OVERVIEW / REVIEW</div>

                <p style="font-size:13px;color:rgba(255,255,255,0.45);line-height:1.8;margin-bottom:18px;">
                    {{ $movie->description ?? 'No description available.' }}
                </p>

                <div style="margin-bottom:13px;">
                    <div style="font-size:10px;letter-spacing:1.5px;color:rgba(255,255,255,0.22);margin-bottom:4px;">DIRECTOR</div>
                    <div style="font-size:13px;color:rgba(255,255,255,0.72);">{{ $movie->director }}</div>
                </div>

                @if($movie->cast)
                <div>
                    <div style="font-size:10px;letter-spacing:1.5px;color:rgba(255,255,255,0.22);margin-bottom:6px;">CAST</div>
                    <div style="display:flex;gap:5px;flex-wrap:wrap;">
                        @foreach(explode(',', $movie->cast) as $c)
                            <span style="font-size:11px;padding:2px 10px;border-radius:20px;background:rgba(255,255,255,0.05);border:1px solid rgba(255,255,255,0.08);color:rgba(255,255,255,0.52);">
                                {{ trim($c) }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            {{-- DIVIDER --}}
            <div style="background:rgba(255,255,255,0.05);"></div>

            {{-- RIGHT: DETAILS --}}
            <div style="padding-left:22px;">
                <div class="section-label" style="margin-bottom:14px;">DETAILS</div>

                @if($movie->release_year)
                <div style="margin-bottom:13px;">
                    <div style="font-size:10px;letter-spacing:1.5px;color:rgba(255,255,255,0.22);margin-bottom:3px;">RELEASE YEAR</div>
                    <div style="font-size:13px;color:rgba(255,255,255,0.72);">{{ $movie->release_year }}</div>
                </div>
                @endif

                @if($movie->duration)
                <div style="margin-bottom:13px;">
                    <div style="font-size:10px;letter-spacing:1.5px;color:rgba(255,255,255,0.22);margin-bottom:3px;">DURATION</div>
                    <div style="font-size:13px;color:rgba(255,255,255,0.72);">{{ $movie->duration }}</div>
                </div>
                @endif

                @if($movie->language)
                <div style="margin-bottom:13px;">
                    <div style="font-size:10px;letter-spacing:1.5px;color:rgba(255,255,255,0.22);margin-bottom:3px;">LANGUAGE</div>
                    <div style="font-size:13px;color:rgba(255,255,255,0.72);">{{ $movie->language }}</div>
                </div>
                @endif

                @if($movie->rating)
                <div style="margin-bottom:13px;">
                    <div style="font-size:10px;letter-spacing:1.5px;color:rgba(255,255,255,0.22);margin-bottom:3px;">RATING</div>
                    <div style="font-size:13px;color:#2979ff;font-weight:600;">
                        <i class="bi bi-star-fill" style="font-size:11px;"></i>
                        {{ number_format($movie->rating,1) }} / 10
                    </div>
                </div>
                @endif

                @if($movie->genre)
                <div>
                    <div style="font-size:10px;letter-spacing:1.5px;color:rgba(255,255,255,0.22);margin-bottom:6px;">GENRE</div>
                    <div style="display:flex;gap:5px;flex-wrap:wrap;">
                        @foreach(explode(',', $movie->genre) as $g)
                            <span class="genre-pill" style="font-size:11px;padding:3px 10px;">{{ trim($g) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

        </div>
    </div>
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