@props(['target', 'scope', 'offset', 'per' => 8, 'template' => 'tpl-entry', 'exclude' => []])
<div class="mt-8 flex justify-center">
    <button type="button" class="wc-btn-line" data-more data-target="{{ $target }}" data-template="{{ $template }}" data-scope="{{ $scope }}" data-offset="{{ $offset }}" data-per="{{ $per }}" data-exclude="{{ implode(',', $exclude) }}">
        Tampilkan tulisan sebelumnya<i class="ri-arrow-down-line"></i>
    </button>
</div>
