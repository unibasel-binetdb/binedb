@php
	$contacts = array();
	$functionId = NULL;

	if($row != NULL) {
		if(array_key_exists('contacts', $row))
			$contacts = $row['contacts'];

		if(array_key_exists('id', $row))
			$functionId = $row['id'];
	}
@endphp

<label>{!! $field['label'] !!}</label>
<table class="table table-striped mt-2">
	<thead>
		<tr><th style="width:20%">{{ trans('contact.topic') }}</th><th>{{ trans('contact.comment') }}</th></tr>
	</thead>
	<tbody>
		@if($contacts && !$contacts->isEmpty())
			@foreach($contacts as $contact)
				<tr>
					<td>{{ $contact['topic']->translate() }}</td>
					<td>{{ $contact['comment'] }}</td>
				</tr>
			@endforeach
		@else
			<tr><td colspan="2" class="text-secondary fs-5">{{ trans('personFunction.noContactsAdded') }}</td></tr>
		@endif

		@if(isset($functionId))
			<tr>
				<td colspan="2">
					<a href="{{ backpack_url('person-function/' . e($functionId) . '/edit#themen') }}">{{ trans('personFunction.manageContacts') }} </a>
				</td>
			</tr>
		@endif
	</tbody>
</table>