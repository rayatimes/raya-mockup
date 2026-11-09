{{-- Ekonomi: grafik lilin yang naik dan tumpukan koin --}}
@php
    $candles = [[1010, 300, 360, 280, 382, false], [1062, 280, 340, 262, 362, true], [1114, 296, 356, 276, 372, false], [1166, 240, 318, 220, 338, true], [1218, 214, 280, 194, 304, true], [1270, 236, 290, 214, 312, false], [1322, 160, 248, 140, 268, true], [1374, 122, 200, 100, 222, true], [1426, 74, 160, 52, 180, true]];
@endphp
<circle class="t1" cx="1240" cy="210" r="250"/>
<path class="ln2" d="M980 110h490M980 210h490M980 310h490"/>
@foreach ($candles as [$x, $top, $bottom, $wickTop, $wickBottom, $isUp])
    <path class="ln" d="M{{ $x + 15 }} {{ $wickTop }}V{{ $wickBottom }}"/>
    <rect class="{{ $isUp ? 't5' : 't3' }}" x="{{ $x }}" y="{{ $top }}" width="30" height="{{ $bottom - $top }}"/>
@endforeach
<path class="acl" d="M985 372l130-44 104-104 106 8 150-170"/>
<path class="acl" d="M1432 56l48-26-6 54"/>
<g>
    @foreach ([404, 374, 344, 314] as $y)
        <path class="t4" d="M1490 {{ $y - 14 }}v22c0 12 34 22 68 22s32-10 32-22v-22z"/>
        <ellipse class="t5" cx="1540" cy="{{ $y - 14 }}" rx="50" ry="16"/>
    @endforeach
</g>
