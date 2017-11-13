{{-- Navigation --}}
<nav class="navbar navbar-default navbar-custom navbar-fixed-top">
    <div class="container-fluid">
        {{-- Brand and toggle get grouped for better mobile display --}}
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#navbar-main">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/blog">{{ config('blog.name') }}</a>
        </div>

        {{-- Collect the nav links, forms, and other content for toggling --}}
        <div class="collapse navbar-collapse" id="navbar-main">
            <ul class="nav navbar-nav">
                <li>
                    <a href="/blog">Home</a>
                </li>

                @if(count($tagCount) > 0)
                    @foreach($tagCount as $item)
                        <li>
                            <a style="text-transform:none" href="/blog?tag={!! $item['tag'] !!}"> {!! $item['tag']."(".$item['count'].")"  !!}</a>
                        </li>

                    @endforeach
                @endif
            </ul>
        </div>


    </div>
</nav>