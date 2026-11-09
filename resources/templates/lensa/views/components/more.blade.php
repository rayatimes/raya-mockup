@props(['target', 'scope', 'offset', 'per' => 8, 'template' => 'tpl-card', 'exclude' => []])
<div class="mt-10 flex justify-center">
    <button type="button" class="ls-btn-ghost" data-more data-target="{{ $target }}" data-template="{{ $template }}" data-scope="{{ $scope }}"
        data-offset="{{ $offset }}" data-per="{{ $per }}" data-exclude="{{ implode(',', $exclude) }}">
        Tampilkan lebih banyak<i class="ri-arrow-down-line"></i>
    </button>
</div>
