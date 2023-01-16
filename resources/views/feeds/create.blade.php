@extends ('template')


@section ('content')
    <div id="wrapper">
        <div id="page" class="container">
            <h1>New Feed</h1>

            <form method="POST" action="/feeds">
                @csrf
                
                <div class="field">
                    <label class="label" for="site_title">Site Title</label>
                        <div class="control">
                            <input 
                            class="input" 
                            type="text" 
                            name="site_title" 
                            id="site_title"
                            value="{{ old('site_title') }}">
                            @error('site_title')
                                <p class="help is-danger">{{ $errors->first('site_title') }}</p>
                            @enderror
                        </div>
                </div>

                <div class="field">
                    <label class="label" for="site_url">Site URL</label>
                        <div class="control">
                            <input 
                            class="input" 
                            type="text" 
                            name="site_url" 
                            id="site_url"
                            value="{{ old('site_url') }}">
                            @error('site_url')
                                <p class="help is-danger">{{ $errors->first('site_url') }}</p>
                            @enderror
                            @if (session('error'))
                                <p class="help is-danger">{{ session('error') }}</p>
                            @endif
                        </div>
                </div>

                <div class="field is-grouped">
                    <div class="control">
                        <button class="button is-link" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection