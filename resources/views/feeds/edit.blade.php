@extends ('template')


@section ('content')
    <div id="wrapper">
        <div id="page" class="container">
            <h1>Update Feed</h1>

            <form method="POST" action="/feeds/{{ $feed->id }}">
                @csrf
                @method('PUT')

                <div class="field">
                    <label class="label" for="site_title">Site Title</label>
                        <div class="control">
                            <input class="input" type="text" name="site_title" id="site_title" value="{{ $feed->site_title }}">
                            @error('site_title')
                                <p class="help is-danger">{{ $errors->first('site_title') }}</p>
                            @enderror
                        </div>
                </div>

                <div class="field">
                    <label class="label" for="site_url">Site URL</label>
                        <div class="control">
                            <input class="input" type="text" name="site_url" id="site_url" value="{{ $feed->site_url }}">
                            @error('site_url')
                                <p class="help is-danger">{{ $errors->first('site_url') }}</p>
                            @enderror
                        </div>
                </div>

                <div class="field is-grouped">
                    <div class="control">
                        <button class="button is-link submit" type="submit">Submit</button>
                        <a href="/" class="button is-link cancel">Cancel</a>
                    </div>
                </div>
            </form>



            <form method="POST" action="/feeds/{{ $feed->id }}">
                @csrf
                @method('DELETE')
                <button class="button">Delete This Feed</button>
            </form>
        </div>
    </div>
@endsection