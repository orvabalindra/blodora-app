@props(['name', 'value' => null, 'minYear' => 1900, 'maxYear' => null, 'required' => true, 'todayShortcut' => false, 'idPrefix' => null])
@php
    $fieldId = $idPrefix ? $idPrefix.'_'.$name : $name;
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
    <div><label class="visually-hidden" for="{{ $fieldId }}_hari">Hari</label><select id="{{ $fieldId }}_hari" name="{{ $name }}_hari" class="form-select @error($name) is-invalid @enderror" @required($required)><option value="">Hari</option>@for ($day = 1; $day <= 31; $day++)<option value="{{ sprintf('%02d', $day) }}" @selected((string) $selectedDay === sprintf('%02d', $day))>{{ $day }}</option>@endfor</select></div>
    <div><label class="visually-hidden" for="{{ $fieldId }}_bulan">Bulan</label><select id="{{ $fieldId }}_bulan" name="{{ $name }}_bulan" class="form-select @error($name) is-invalid @enderror" @required($required)><option value="">Bulan</option>@foreach ($months as $number => $month)<option value="{{ $number }}" @selected((string) $selectedMonth === $number)>{{ $month }}</option>@endforeach</select></div>
    <div><label class="visually-hidden" for="{{ $fieldId }}_tahun">Tahun</label><select id="{{ $fieldId }}_tahun" name="{{ $name }}_tahun" class="form-select @error($name) is-invalid @enderror" @required($required)><option value="">Tahun</option>@for ($year = $minYear; $year <= $maxYear; $year++)<option value="{{ $year }}" @selected((string) $selectedYear === (string) $year)>{{ $year }}</option>@endfor</select></div>
</div>
<input
    type="date"
    id="{{ $fieldId }}_calendar"
    value="{{ $dateParts ?: now()->format('Y-m-d') }}"
    min="{{ sprintf('%04d-01-01', $minYear) }}"
    max="{{ sprintf('%04d-12-31', $maxYear) }}"
    class="visually-hidden"
    tabindex="-1"
    aria-hidden="true"
    onchange="if(this.value){const d=this.value.split('-');document.getElementById('{{ $fieldId }}_tahun').value=d[0];document.getElementById('{{ $fieldId }}_bulan').value=d[1];document.getElementById('{{ $fieldId }}_hari').value=d[2];}"
>
<button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="const p=document.getElementById('{{ $fieldId }}_calendar');if(p.showPicker){p.showPicker();}else{p.click();}">
    <i class="bi bi-calendar3 me-1" aria-hidden="true"></i>Pilih dari kalender
</button>
@if ($todayShortcut)
    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" onclick="const d='{{ now()->format('Y-m-d') }}'.split('-'); document.getElementById('{{ $fieldId }}_tahun').value=d[0]; document.getElementById('{{ $fieldId }}_bulan').value=d[1]; document.getElementById('{{ $fieldId }}_hari').value=d[2]; document.getElementById('{{ $fieldId }}_calendar').value=d.join('-');">Gunakan tanggal hari ini</button>
@endif
