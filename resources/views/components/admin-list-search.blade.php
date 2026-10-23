<form method="GET" action="{{ $action }}" class="admin-list-search mb-3">
    <div class="input-group">
        <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="{{ $placeholder }}" aria-label="{{ $placeholder }}">
        <button type="submit" class="btn btn-danger">Cari</button>
        @if (request()->filled('q'))
            <a href="{{ $action }}" class="btn btn-outline-secondary" aria-label="Hapus kata pencarian" title="Hapus kata pencarian"><i class="bi bi-x-lg" aria-hidden="true"></i></a>
        @endif
    </div>
    <small class="text-muted d-block mt-2">Menampilkan {{ $items->firstItem() ?? 0 }}–{{ $items->lastItem() ?? 0 }} dari {{ $items->total() }} data.</small>
</form>
