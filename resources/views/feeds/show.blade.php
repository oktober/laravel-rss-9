@extends ('template')


@section ('content')
    <section id="main" class="wrapper">
        <div class="inner">
            <header class="align-center">
                <h1>{{ $feed->site_title }}</h1>
                <p><a href="{{ $feed->site_url }}" target="_blank">
                    {{ $feed->site_url }}
                    <img width="10" alt="External link font awesome" src="https://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/External_link_font_awesome.svg/512px-External_link_font_awesome.svg.png">
                </a></p>
            </header>

            @foreach ($feed->entries as $entry)
            <h2><a href="/entry/{{ $entry->id }}">{{ $entry->entry_title }}</a></h2>
            <h3>Posted: {{ $entry->entry_last_updated->diffForHumans() }}</h3>

            <p>{{ $entry->entry_teaser }}</p>
            @endforeach

        </div>

        <div class="inner">
            <p><a href="{{ Request::url() }}/edit">Edit This Feed</a>
        </div>
    </section>
@endsection