@extends ('template')


@section ('content')
    <section id="main" class="wrapper">
        <div class="inner">
            <header class="align-center">
                <h1><a href="{{ route('feeds.show', $entry->feed->id) }}">{{ $entry->feed->site_title }}</a></h1>
            </header>
            
            <h2>{{ $entry->entry_title }}</h2>
            <p><a href="{{ $entry->entry_url }}" target="_blank">
                {{ $entry->entry_url }}
                <img width="10" alt="External link font awesome" src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/External_link_font_awesome.svg/512px-External_link_font_awesome.svg.png">
            </a></p>
            <h3>Posted: {{ $entry->entry_last_updated->toFormattedDateString() }}</h3>

            <p>{!! $entry->entry_content !!}</p>
        </div>

        <div class="inner">
            <p><a href="{{ route('feeds.show', $entry->feed->id) }}">Back to Feed</a>
        </div>
    </section>
@endsection