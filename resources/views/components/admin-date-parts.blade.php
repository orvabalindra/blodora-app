@props(['name', 'value' => null, 'minYear' => 1900, 'maxYear' => null, 'required' => true])
@php
    $dateValue = old($name, $value);
    try { $dateParts = $dateValue ? \Carbon\Carbon::parse($dateValue)->format('Y-m-d') : ''; } catch (\Throwable $e) { $dateParts = ''; }
    [$selectedYear, $selectedMonth, $selectedDay] = $dateParts ? explode('-', $dateParts) : ['', '', ''];
    $selectedDay = old($name.'_hari', $selectedDay);
    $selectedMonth = old($name.'_bulan', $selectedMonth);
    $selectedYear = old($name.'_tahun', $selectedYear ?: now()->year);
    $maxYear = $maxYear ?? now()->year + 10;
    $minYear = min((int) $minYear, (int) $selectedYear);
    $maxYear = max((int) $maxYear, (int) $selectedYear);
    $months = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
@endphp
<div {{ $attributes->merge(['class' => 'admin-date-parts']) }}>
    <div><label class="visually-hidden" for="{{ $name }}_hari">Hari</label><select id="{{ $name }}_hari" name="{{ $name }}_hari" class="form-select @error($name) is-invalid @enderror" @required($required)><option value="">Hari</option>@for ($day = 1; $day <= 31; $day++)<option value="{{ sprintf('%02d', $day) }}" @selected((string) $selectedDay === sprintf('%02d', $day))>{{ $day }}</option>@endfor</select></div>
    <div><label class="visually-hidden" for="{{ $name }}_bulan">Bulan</label><select id="{{ $name }}_bulan" name="{{ $name }}_bulan" class="form-select @error($name) is-invalid @enderror" @required($required)><option value="">Bulan</option>@foreach ($months as $number => $month)<option value="{{ $number }}" @selected((string) $selectedMonth === $number)>{{ $month }}</option>@endforeach</select></div>
    <div><label class="visually-hidden" for="{{ $name }}_tahun">Tahun</label><select id="{{ $name }}_tahun" name="{{ $name }}_tahun" class="form-select @error($name) is-invalid @enderror" @required($required)><option value="">Tahun</option>@for ($year = $minYear; $year <= $maxYear; $year++)<option value="{{ $year }}" @selected((string) $selectedYear === (string) $year)>{{ $year }}</option>@endfor</select></div>
</div>
