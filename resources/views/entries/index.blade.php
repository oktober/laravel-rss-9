@extends ('template')

@section ('content')
	<h1>Entries</h1>

	@foreach ($entries as $entry)
	<div>{{ $entry->entry_title}} </div>
	<div>{!! $entry->entry_content !!} </div>
	@endforeach
@endsection