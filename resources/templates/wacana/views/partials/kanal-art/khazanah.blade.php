{{-- Khazanah: stupa candi, pola kawung, dan gunungan wayang --}}
@php
    $stupa = 'c0-46-30-68-46-76v-38l-14-34-14 34v38c-16 8-46 30-46 76z';
@endphp
@for ($row = 0; $row < 3; $row++)
    @for ($col = 0; $col < 6; $col++)
        @foreach ([45, 135, 225, 315] as $angle)
            <ellipse class="t2" cx="{{ 1022 + $col * 64 }}" cy="{{ 28 + $row * 64 }}" rx="10" ry="17" transform="rotate({{ $angle }} {{ 1022 + $col * 64 }} {{ 48 + $row * 64 }})"/>
        @endforeach
    @endfor
@endfor
<path class="t2" d="M980 420v-32h60v-34h60v-34h300v34h60v34h20v32z"/>
<path class="t3" d="M1040 420v-32h60v-34h300v34h60v32z"/>
<path class="t5" d="M1310 320{{ $stupa }}" transform="translate(-60 0) scale(1 1.16) translate(0 -44)"/>
<path class="t4" d="M1150 354{{ $stupa }}"/>
<path class="t4" d="M1470 354{{ $stupa }}" transform="translate(-60 0)"/>
<path class="t5" d="M1490 44c64 76 98 176 86 282l12 94h-196l12-94c-12-106 22-206 86-282z"/>
<path class="cutl" d="M1490 110v232M1490 170l-40-38M1490 170l40-38M1490 220l-58-52M1490 220l58-52M1490 272l-64-56M1490 272l64-56M1442 420v-52h96v52"/>
<circle class="ac" cx="1490" cy="106" r="13"/>
