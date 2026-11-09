{{-- Formula 1: mobil balap dan bendera kotak-kotak --}}
<circle class="t1" cx="1290" cy="200" r="245"/>
@for ($row = 0; $row < 4; $row++)
    @for ($col = 0; $col < 8; $col++)
        @if (($row + $col) % 2 === 0)
            <rect class="t4" x="{{ 1110 + $col * 34 }}" y="{{ 26 + $row * 34 + ($col % 4 === 0 ? 0 : 4) }}" width="34" height="34"/>
        @endif
    @endfor
@endfor
<path class="ln" d="M1110 26v160"/>
<path class="ln2" d="M930 296h76M900 326h96M940 356h60"/>
<path class="ln2" d="M930 396h670"/>
<path class="t5" d="M1040 332h90l20-30 118-14c32-34 62-52 94-54l14-22h34l10 24c46 8 92 22 138 26l2 70h-40a58 58 0 0 0-112 0h-186a50 50 0 0 0-96 0z"/>
<path class="t5" d="M1020 340h136v16h-136z"/>
<path class="t5" d="M1516 188h74v16h-74zM1572 188h14v118h-14z"/>
<path class="ln" d="M1272 288c18-24 50-44 80-48 22 2 32 12 46 26"/>
<circle class="t5" cx="1176" cy="350" r="46"/><circle class="cut" cx="1176" cy="350" r="24"/><circle class="ac" cx="1176" cy="350" r="9"/>
<circle class="t5" cx="1474" cy="344" r="52"/><circle class="cut" cx="1474" cy="344" r="28"/><circle class="ac" cx="1474" cy="344" r="10"/>
