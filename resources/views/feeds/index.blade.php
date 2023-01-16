@extends ('template')

@section ('content')
	<h1>Feeds</h1>

	@foreach ($feeds as $feed)
	<div>{{ $feed->site_title}} </div>
	<div>{{ $feed->site_url}} </div>
	<div>{{ $feed->last_updated}} </div>
	@endforeach
@endsection