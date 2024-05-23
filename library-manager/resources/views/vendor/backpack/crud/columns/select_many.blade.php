@php
    $column['value'] = $column['value'] ?? data_get($entry, $column['property'], collect([]));
    $column['escaped'] = $column['escaped'] ?? true;
    $column['prefix'] = $column['prefix'] ?? '';
    $column['suffix'] = $column['suffix'] ?? '';
    $column['limit'] = $column['limit'] ?? 32;
    $column['attribute'] = $column['attribute'] ?? (new $column['model'])->identifiableAttribute();

    if($column['value'] instanceof \Closure) {
        $column['value'] = $column['value']($entry);
    }
  
    $entries = $column['value'];
    $keyName = 'id';

  
    if($column['value'] !== null && !$column['value']->isEmpty()) {
        $related_key = $column['value']->first()->getKeyName();
        $keyName = $related_key;
       /* if(isset($column['opaqueBy'])) {
            dd($column['value']);
            $column['opaque'] = true;//$column['opaqueBy']($column['value']);  // $column['value']->pluck($column['opaqueProperty'], $related_key);
          //  dd( $column['opaque']);
        }*/

        if(isset($column['sortBy']))
            $column['value'] = $column['value']->sortBy($column['sortBy']);

        $column['value'] = $column['value']->pluck($column['attribute'], $related_key);
    }

    $column['value'] = $column['value']
        ->map(function($value) use ($column) {
            $hdnl = $value;
            if(isset($column['additional_function']))
                $hdnl = $hdnl->{$column['additional_function']}();

            return Str::limit($hdnl, $column['limit'], '…');
        })
        ->toArray();

@endphp

<div class="group">
    @if(!empty($column['value']))
        {{ $column['prefix'] }}
        @foreach($column['value'] as $key => $text)
            @php
                $related_key = $key;
            @endphp

            @php
                $entry = $entries->first(function ($value, $sp) use ($keyName, $key) {
                    return $value[$keyName] == $key;
                });
        
                $className = '';
                if(isset($column['opaqueBy'])) {
                    if($column['opaqueBy']($entry))
                        $className = 'opacity-30';
                }

                $url = NULL;
                if(isset($column['urlResolver']))
                    $url = $column['urlResolver']($entry);
            @endphp

            <div class="{!! $className !!}">
                @includeWhen(!empty($column['wrapper']), 'crud::columns.inc.wrapper_start')
                    @if($url)
                        <a href="{!! $url !!}">
                            @if($column['escaped'])
                                {{ $text ?? "-" }}
                            @else
                                {!! $text ?? "-" !!}
                            @endif
                        </a>
                    @else
                        @if($column['escaped'])
                            {{ $text ?? "-" }}
                        @else
                            {!! $text ?? "-" !!}
                        @endif
                    @endif
                @includeWhen(!empty($column['wrapper']),'crud::columns.inc.wrapper_end')
            </div>
        @endforeach
        {{ $column['suffix'] }}
    @else
        {{ $column['default'] ?? '-' }}
    @endif
</div>
