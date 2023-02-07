@extends ('template')


@section ('content')
    <section id="three" class="wrapper">

        {{-- For displaying messages like 'Feed successfully deleted' --}}
        @if (session()->get('success'))
        <div class="inner">
            {{ session()->get('success') }}
        </div>
        @endif
        
        <div class="inner">
            <h1>Blog Feed</h1>
        </div>

        @forelse ($feeds as $feed)
        <div class="inner">
            <h2><a href="{{ route('feeds.show', $feed) }}">{{ $feed->site_title }}</a></h2>
        </div>
        <div class="inner flex flex-3">
            @foreach ($feed->entries as $entry)
                {{-- Only want to show the first 3 entries for a feed --}}
                @if ($loop->index > 2) 
                    @break
                @endif
                <div class="flex-item box">
                    <div class="content">
                        <h3><a href="/entry/{{ $entry->id }}">{{ $entry->entry_title }}</a></h3>
                        <p>{{ $entry->entry_teaser }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        @empty
        <div class="inner">
            <h2>No feeds available</h2>
            <p>
                <a href="{{ route('feeds.create') }}">Enter a new feed</a>
            </p>
        </div>

        @endforelse

    </section>
@endsection