{{-- regular object attribute --}}
@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['name']);
    $column['escaped'] = $column['escaped'] ?? true;
    $column['limit'] = $column['limit'] ?? 32;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['text'] = $column['default'] ?? '-';

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }

    if(is_array($column['value'])) {
        $column['value'] = json_encode($column['value']);
    }

    if(!empty($column['value'])) {
        $column['text'] = $column['prefix'].$column['value'].$column['suffix'];
        $column['shortenedText'] = $column['prefix'].Str::limit($column['value'], $column['limit'], '…').$column['suffix'];
    }
@endphp

<span title="{{ $column['text'] }}">
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
        @if($column['escaped'])
            {{ $column['shortenedText'] }}
        @else
            {!! $column['shortenedText'] !!}
        @endif
    @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_end')
</span>
