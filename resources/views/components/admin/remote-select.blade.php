@props([
    'name',
    'id',
    'label',
    'url',
    'placeholder',
    'selectedId' => null,
    'selectedText' => null,
    'required' => false,
])

<div class="admin-remote-select" data-remote-select-search>
    <label for="{{ $id }}-search" class="admin-form-label">Cari {{ strtolower($label) }}</label>
    <input
        type="search"
        id="{{ $id }}-search"
        class="admin-form-control"
        data-remote-search-input
        data-target="{{ $id }}"
        data-url="{{ $url }}"
        value="{{ $selectedText }}"
        placeholder="Ketik minimal 2 karakter"
        autocomplete="off"
        aria-describedby="{{ $id }}-search-status"
    >
    <small id="{{ $id }}-search-status" class="form-text" data-remote-search-status role="status" aria-live="polite">
        {{ $selectedText ? 'Pilihan saat ini: '.$selectedText : 'Ketik nama atau identitas untuk mencari.' }}
    </small>

    <label for="{{ $id }}" class="admin-form-label mt-2">{{ $label }}</label>
    <select
        id="{{ $id }}"
        name="{{ $name }}"
        class="admin-form-select @error($name) is-invalid @enderror"
        data-remote-options="true"
        @required($required)
    >
        <option value="">{{ $placeholder }}</option>
        @if ($selectedId && $selectedText)
            <option value="{{ $selectedId }}" selected>{{ $selectedText }}</option>
        @endif
    </select>

    @error($name)
        <div class="admin-form-error">{{ $message }}</div>
    @enderror
</div>
