<?php
use Illuminate\Support\Facades\Route;
?>

@section('breadcrumbs')
@endsection

<article class="">
    <div class="navbar navbar-default">

        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('log-viewer::dashboard') }}">Log Viewer</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="{{ Route::is('log-viewer::dashboard') ? 'active' : '' }}">
                    <a href="{{ route('log-viewer::dashboard') }}">
                        <i class="fa fa-dashboard"></i> Dashboard
                    </a>
                </li>
                <li class="{{ Route::is('log-viewer::logs.list') ? 'active' : '' }}">
                    <a href="{{ route('log-viewer::logs.list') }}">
                        <i class="fa fa-archive"></i> Logs
                    </a>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div>
</article>
