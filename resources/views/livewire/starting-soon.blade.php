<div>
    @unless ($tournament)
        no tournament
        @else
        <img src="{{ $tournament->logo_url }}" alt="Tournament Logo" class="absolute top-0 w-64 object-scale-down">
    @endunless
</div>
