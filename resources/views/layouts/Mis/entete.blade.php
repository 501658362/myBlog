@if(isset($subs) && !empty($subs))
    @foreach($subs as $sub)
        @if(isset($sub['title']))
        <li class="active">
            @if(isset($sub['url']))<a href="{!! $sub['url'] !!}">@endif
                {!! $sub['title'] !!}
                @if(isset($sub['url']))</a> @endif
        </li>
        @endif
    @endforeach
@endif