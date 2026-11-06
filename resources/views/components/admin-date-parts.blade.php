@props([
    'name',
    'value' => null,
    'minYear' => 1900,
    'maxYear' => null,
    'minDate' => null,
    'maxDate' => null,
    'required' => true,
    'idPrefix' => null,
])
@php
    $fieldId = $idPrefix ? $idPrefix.'_'.$name : $name;
    $dateValue = old($name, $value);

    try {
        $dateValue = $dateValue ? \Carbon\Carbon::parse($dateValue)->format('Y-m-d') : '';
    } catch (\Throwable $e) {
        $dateValue = '';
    }

    $selectedYear = $dateValue ? (int) substr($dateValue, 0, 4) : (int) now()->year;
    $minYear = min((int) $minYear, $selectedYear);
    $maxYear = max((int) ($maxYear ?? now()->year + 10), $selectedYear);
    $minDate = $minDate ? \Carbon\Carbon::parse($minDate)->format('Y-m-d') : sprintf('%04d-01-01', $minYear);
    $maxDate = $maxDate ? \Carbon\Carbon::parse($maxDate)->format('Y-m-d') : sprintf('%04d-12-31', $maxYear);
@endphp

<div {{ $attributes->merge(['class' => 'admin-date-parts']) }}>
    <input
        type="date"
        id="{{ $fieldId }}"
        name="{{ $name }}"
        value="{{ $dateValue }}"
        min="{{ $minDate }}"
        max="{{ $maxDate }}"
        class="form-control @error($name) is-invalid @enderror"
        placeholder="Pilih tanggal"
        @required($required)
    >
</div>
