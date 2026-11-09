@props(['target', 'scope', 'offset', 'per' => 10, 'template' => 'tpl-row', 'exclude' => [], 'label' => 'Lihat lainnya'])
<div data-loadmore-wrap class="mt-8 flex justify-center">
    <button type="button" class="rt-btn" data-loadmore data-target="{{ $target }}" data-template="{{ $template }}" data-skeleton="{{ $template }}-skeleton"
        data-scope="{{ $scope }}" data-offset="{{ $offset }}" data-per="{{ $per }}" data-exclude="{{ implode(',', $exclude) }}" data-label="{{ $label }}">
        <span data-label>{{ $label }}</span><i class="ri-arrow-down-line"></i>
    </button>
</div>
