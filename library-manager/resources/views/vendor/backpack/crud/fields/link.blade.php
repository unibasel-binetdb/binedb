@includeWhen(!isset($field['wrapper']) || $field['wrapper'] !== false, 'crud::fields.inc.wrapper_start')

@php
	$link = isset($entry) ? $field['link']($entry, isset($row) ? $row : NULL) : NULL;
@endphp

@if($link != NULL)
<div class="pb-2">
	@if(isset($field['style']) && $field['style'] == 'btn')
		<a class="btn btn-primary" href="{{ backpack_url($link) }}">{!! $field['label'] !!}</a>
	@else
		<a href="{{ backpack_url($link) }}">{!! $field['label'] !!}</a>
	@endif
	
</div>
@endif
@includeWhen(!isset($field['wrapper']) || $field['wrapper'] !== false, 'crud::fields.inc.wrapper_end')