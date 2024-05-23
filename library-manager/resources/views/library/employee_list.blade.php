@php

    $functions = $entry != null ? $entry->functions->sortBy('person.last_name') : collect();
    if(isset($field['institution'])) {
        $functions = $functions->filter(function($f) use ($field) {
            if($f->exited)
                return false;
            
            return $f->institution == $field['institution'];
        });
    }
@endphp

@if (isset($field['label'] ))
    <label>{{ $field['label'] }}</label>
@endif
<table class="table table-striped nowrap rounded card-table table-vcenter card d-table">
    <thead>
        <tr>
            <th>{{ trans('person.lastName') }}</th>
            <th>{{ trans('person.firstName') }}</th>
            <th>{{ trans('personFunction.work') }}</th>
            <th>{{ trans('personFunction.email') }}</th>
            <th>{{ trans('personFunction.phone') }}</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        @if(count($functions) == 0)
            <tr class="odd">
                <td class="dataTables_empty" valign="top" colspan="6">{{ trans('library.noEmployees') }}</td>
            </tr>
        @else
            @foreach($functions as $function)
                <tr class="odd">
                    <td>{{ $function->person->last_name }}</td>
                    <td>{{ $function->person->first_name }}</td>
                    <td>
                        {{ $function->work?->translate() }}
                    </td>
                    <td>
                        @if(isset($function->email))
                            <a href="mailto:{{ $function->email }}">{{ $function->email }}</a>
                        @endif
                    </td>
                    <td>{{ $function->phone }}</td>
                    <td>
                        <a href="{{ backpack_url('person-function/' . e($function->id) . '/edit') }}" class="btn btn-sm btn-link">
                            <span><i class="la la-edit"></i> {{ trans('person.edit') }}</span>
                        </a>
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>