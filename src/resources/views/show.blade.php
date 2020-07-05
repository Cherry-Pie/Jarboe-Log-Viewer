<?php
/**
 * @var  Arcanedev\LogViewer\Entities\Log            $log
 * @var  Illuminate\Pagination\LengthAwarePaginator  $entries
 * @var  string|null                                 $query
 */
?>

@extends('jarboe::layouts.main')

@section('content')
    @include('log-viewer::jarboe.inc.styles')

    @include('log-viewer::jarboe.inc.navigation')

    <h1 class="page-header">Log [{{ $log->date }}]</h1>

    <div class="row">
        <div class="col-md-2">
            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-fw fa-flag"></i> Levels</div>
                <ul class="list-group">
                    @foreach($log->menu() as $levelKey => $item)
                        @if ($item['count'] === 0)
                            <a href="#" class="list-group-item disabled">
                                <span class="badge">
                                    {{ $item['count'] }}
                                </span>
                                {!! $item['icon'] !!} {{ $item['name'] }}
                            </a>
                        @else
                            <a href="{{ $item['url'] }}" class="list-group-item {{ $levelKey }}">
                                <span class="badge level-{{ $levelKey }}">
                                    {{ $item['count'] }}
                                </span>
                                <span class="level level-{{ $levelKey }}">
                                    {!! $item['icon'] !!} {{ $item['name'] }}
                                </span>
                            </a>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="col-md-10">
            {{-- Log Details --}}
            <div class="panel panel-default">
                <div class="panel-heading">
                    &nbsp;
                    <div class="group-btns pull-right">
                        <a href="{{ route('log-viewer::logs.download', [$log->date]) }}" class="btn btn-xs btn-success">
                            <i class="fa fa-download"></i> Download
                        </a>
                        <a href="#" class="btn btn-xs btn-danger delete-log">
                            <i class="fa fa-trash-o"></i> Delete
                        </a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <td>File path:</td>
                                <td colspan="7">{{ $log->getPath() }}</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Log entries: </td>
                                <td>
                                    <span class="label label-primary">{{ $entries->total() }}</span>
                                </td>
                                <td>Size:</td>
                                <td>
                                    <span class="label label-primary">{{ $log->size() }}</span>
                                </td>
                                <td>Created at:</td>
                                <td>
                                    <span class="label label-primary">{{ $log->createdAt() }}</span>
                                </td>
                                <td>Updated at:</td>
                                <td>
                                    <span class="label label-primary">{{ $log->updatedAt() }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    {{-- Search --}}
                    <form action="{{ route('log-viewer::logs.search', [$log->date, $level]) }}" method="GET">
                        <div class=form-group">
                            <div class="input-group">
                                <input id="query" name="query" class="form-control"  value="{{ $query }}" placeholder="Type here to search">
                                <span class="input-group-btn">
                                    @unless (is_null($query))
                                        <a href="{{ route('log-viewer::logs.show', [$log->date]) }}" class="btn btn-default">
                                            <span class="glyphicon glyphicon-remove"></span>
                                        </a>
                                    @endunless
                                    <button id="search-btn" class="btn btn-primary">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Log Entries --}}
            <div class="panel panel-default">
                @if ($entries->hasPages())
                    <div class="panel-heading">
                        {{ $entries->appends(compact('query'))->render() }}

                        <span class="label label-info pull-right">
                            Page {{ $entries->currentPage() }} of {{ $entries->lastPage() }}
                        </span>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="entries" class="table table-condensed">
                        <thead>
                            <tr>
                                <th>ENV</th>
                                <th style="width: 120px;">Level</th>
                                <th style="width: 65px;">Time</th>
                                <th>Header</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($entries as $key => $entry)
                                <?php /** @var  Arcanedev\LogViewer\Entities\LogEntry  $entry */ ?>
                                <tr>
                                    <td>
                                        <span class="label label-env">{{ $entry->env }}</span>
                                    </td>
                                    <td>
                                        <span class="level level-{{ $entry->level }}">{!! $entry->level() !!}</span>
                                    </td>
                                    <td>
                                        <span class="label label-default">
                                            {{ $entry->datetime->format('H:i:s') }}
                                        </span>
                                    </td>
                                    <td>
                                        <p>{{ $entry->header }}</p>
                                    </td>
                                    <td class="text-right">
                                        @if ($entry->hasStack())
                                            <a class="btn btn-xs btn-default" role="button" data-toggle="collapse" href="#log-stack-{{ $key }}" aria-expanded="false" aria-controls="log-stack-{{ $key }}">
                                                <i class="fa fa-toggle-on"></i> Stack
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                                @if ($entry->hasStack())
                                    <tr>
                                        <td colspan="5" class="stack">
                                            <div class="stack-content collapse" id="log-stack-{{ $key }}">
                                                {!! $entry->stack() !!}
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <span class="label label-default">{{ trans('log-viewer::general.empty-logs') }}</span>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($entries->hasPages())
                    <div class="panel-footer">
                        {{ $entries->appends(compact('query'))->render() }}

                        <span class="label label-info pull-right">
                            Page {{ $entries->currentPage() }} of {{ $entries->lastPage() }}
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(function () {
            $('a.delete-log').on('click', function(e) {
                e.preventDefault();

                jarboe.confirmBox({
                    title: "Delete log file?",
                    content: "Log file for date <code>{{ $log->date }}</code> will be deleted. This action cannot be undone.",
                    buttons: {
                        'Delete': function() {
                            $.ajax({
                                url: "{{ route('log-viewer::logs.delete') }}",
                                type: "DELETE",
                                dataType: 'json',
                                data: {
                                    date: "{{ $log->date }}"
                                },
                                success: function(data) {
                                    if (data.result === 'success') {
                                        window.location.replace("{{ route('log-viewer::logs.list') }}");
                                    } else {
                                        jarboe.smallToast({
                                            title: 'Can not delete file',
                                            content: 'Check file permissions or delete it manually',
                                            timeout: 0,
                                            color: '#C46A69',
                                            icon: 'fa fa-warning shake animated',
                                        });
                                    }
                                },
                                error: function(xhr, textStatus, errorThrown) {
                                    jarboe.bigToast({
                                        title: 'Unhandled Error',
                                        content: errorThrown,
                                        timeout: 0,
                                        color: '#C46A69',
                                        icon: 'fa fa-warning shake animated',
                                    });
                                }
                            });
                        },
                        'Cancel': null,
                    },
                });
            });

            @unless (empty(log_styler()->toHighlight()))
            $('.stack-content').each(function() {
                var $this = $(this);
                var html = $this.html().trim()
                    .replace(/({!! implode('|', log_styler()->toHighlight()) !!})/gm, '<strong>$1</strong>');

                $this.html(html);
            });
            @endunless
        });
    </script>
@endpush
