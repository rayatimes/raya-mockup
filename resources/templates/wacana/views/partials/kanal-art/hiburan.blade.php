{{-- Hiburan: papan klap film, gulungan film, dan not musik --}}
<circle class="t1" cx="1270" cy="205" r="250"/>
<path class="t1" d="M1500-10h-70l-140 430h340z"/>
<g transform="rotate(9 1500 210)">
    <rect class="t4" x="1452" y="10" width="104" height="420"/>
    @for ($y = 24; $y < 420; $y += 40)
        <rect class="cut" x="1462" y="{{ $y }}" width="14" height="20"/>
        <rect class="cut" x="1532" y="{{ $y }}" width="14" height="20"/>
    @endfor
    <rect class="cut" x="1484" y="40" width="40" height="86"/><rect class="cut" x="1484" y="140" width="40" height="86"/><rect class="cut" x="1484" y="240" width="40" height="86"/>
</g>
<rect class="t5" x="1080" y="184" width="316" height="226"/>
<path class="cutl" d="M1110 252h256M1110 300h256M1110 348h150"/>
<g transform="rotate(-12 1080 170)">
    <rect class="t5" x="1080" y="128" width="316" height="44"/>
    @for ($x = 1094; $x < 1380; $x += 70)
        <path class="cut" d="M{{ $x }} 128h34l-22 44h-34z"/>
    @endfor
</g>
<path class="ac" transform="translate(1040 24) scale(.62)" d="M94 66v116a30 24 0 1 1-16-20V96l-62 16v88a30 24 0 1 1-16-20V84z"/>
