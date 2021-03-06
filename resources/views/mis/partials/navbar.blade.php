<ul class="nav navbar-nav">
    <li><a href="/blog">{!! trans('language.index') !!}</a></li>
    @if (Auth::check())
        <li @if (Request::is('mis/post*')) class="active" @endif>
            <a href="/mis/post">{!! trans('language.posts') !!}</a>
        </li>
        <li @if (Request::is('mis/tag*')) class="active" @endif>
            <a href="/mis/tag">{!! trans('language.tags') !!}</a>
        </li>
        <li @if (Request::is('mis/upload*')) class="active" @endif>
            <a href="/mis/upload">{!! trans('language.uploads') !!}</a>
        </li>
    @endif
</ul>

<ul class="nav navbar-nav navbar-right">
    @if (Auth::guest())
        <li><a href="/auth/login">Login</a></li>
    @else
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
               aria-expanded="false">
                {{ Auth::user()->name }}
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" role="menu">
                <li><a href="/auth/logout">Logout</a></li>
            </ul>
        </li>
    @endif
</ul>