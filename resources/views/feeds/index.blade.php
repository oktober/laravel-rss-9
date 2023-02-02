@extends ('template')

@section ('content')
    <section id="three" class="wrapper">
        <div class="inner">
			<h1>Feeds</h1>
		</div>

        <div class="inner">
			@if ($feeds->isNotEmpty())
				@foreach ($feeds as $feed)
				<h2>
					<a href="/feeds/{{ $feed->id }}">
						{{ $feed->site_title}}
					</a>
				</h2>
				<p>{{ $feed->site_url}}</p>
				<p>Last updated: {{ $feed->updated_at}}</p>
				@endforeach
			@else
				<h2>No feeds found</h2>
			@endif
		</div>
	</section>
@endsection