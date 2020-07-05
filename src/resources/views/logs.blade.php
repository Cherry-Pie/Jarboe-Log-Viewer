@extends('jarboe::layouts.main')

@section('content')
    @include('log-viewer::jarboe.inc.styles')
    @include('log-viewer::jarboe.inc.navigation')

    <h1 class="page-header">Logs</h1>

    {!! $rows->render() !!}

    <div class="panel panel-default">
        <div class="table-responsive">
        <table class="table table-condensed table-hover table-stats">
            <thead>
                <tr>
                    @foreach($headers as $key => $header)
                    <th class="{{ $key == 'date' ? 'text-left' : 'text-center' }}">
                        @if ($key == 'date')
                            <span class="label label-info">{{ $header }}</span>
                        @else
                            <span class="level level-{{ $key }}">
                                {!! log_styler()->icon($key) . ' ' . $header !!}
                            </span>
                        @endif
                    </th>
                    @endforeach
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if ($rows->count() > 0)
                    @foreach($rows as $date => $row)
                    <tr>
                        @foreach($row as $key => $value)
                            <td class="{{ $key == 'date' ? 'text-left' : 'text-center' }}">
                                @if ($key == 'date')
                                    <span class="label label-primary">{{ $value }}</span>
                                @elseif ($value == 0)
                                    <span class="level level-empty">{{ $value }}</span>
                                @else
                                    <a href="{{ route('log-viewer::logs.filter', [$date, $key]) }}">
                                        <span class="level level-{{ $key }}">{{ $value }}</span>
                                    </a>
                                @endif
                            </td>
                        @endforeach
                        <td class="text-right">
                            <a href="{{ route('log-viewer::logs.show', [$date]) }}" class="btn btn-xs btn-info">
                                <i class="fa fa-search"></i>
                            </a>
                            <a href="{{ route('log-viewer::logs.download', [$date]) }}" class="btn btn-xs btn-success">
                                <i class="fa fa-download"></i>
                            </a>
                            <a href="#" class="btn btn-xs btn-danger delete-log" data-log-date="{{ $date }}">
                                <i class="fa fa-trash-o"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="11" class="text-center">
                            <span class="label label-default">{{ trans('log-viewer::general.empty-logs') }}</span>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
    </div>

    {!! $rows->render() !!}
@endsection

@pushonce('scripts', <script src="/vendor/jarboe/js/plugin/chartjs/chart.min.js"></script>)

@push('scripts')
    <script>
        Chart.defaults.global.responsive = true;
        Chart.defaults.global.animationEasing = "easeOutQuart";
    </script>

    <script>
        $(function () {
            $('a.delete-log').on('click', function(e) {
                e.preventDefault();

                jarboe.confirmBox({
                    title: "Delete log file?",
                    content: "Log file for date <code>"+ $(this).data('log-date') +"</code> will be deleted. This action cannot be undone.",
                    buttons: {
                        'Delete': function() {
                            $.ajax({
                                url: "{{ route('log-viewer::logs.delete') }}",
                                type: "DELETE",
                                dataType: 'json',
                                data: {
                                    date: $(this).data('log-date')
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
        });
    </script>
@endpush
