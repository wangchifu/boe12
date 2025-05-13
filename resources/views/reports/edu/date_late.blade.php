@extends('layouts.app_clean')

@section('title','延期 | ')

@section('content')
    <script src="{{ asset('gijgo/js/gijgo.min.js') }}" type="text/javascript"></script>
    <link href="{{ asset('gijgo/css/gijgo.min.css') }}" rel="stylesheet" type="text/css">
    <div class="container">
        <div class="col-12">
            <div class="card">
                <div class="card-head">
                    <img class="card-img-top img-responsive" src="{{ asset('images/small/date_late.png') }}">
                </div>
                <div class="card-body">
                    <form action="{{ route('edu_report.save_date_late',$report->id) }}" method="post" enctype="multipart/form-data" id="this_form">
                        @csrf
                        @method('patch')
                        <div class="form-group">
                            <h4>{{ $report->name }}</h4>
                        </div>
                        <div class="form-group">
                            <label for="die_date"><strong>截止日期*</strong></label>
                            <input id="die_date" name="die_date" required maxlength="10" placeholder="十碼：2019-01-01" class="form-control" width="250" value="{{ $report->die_date }}">
                            <script src="{{ asset('gijgo/js/messages/messages.zh-TW.js') }}"></script>
                            <script>
                                $('#die_date').datepicker({
                                    uiLibrary: 'bootstrap4',
                                    format: 'yyyy-mm-dd',
                                    locale: 'zh-TW',
                                });
                            </script>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-success btn-sm" onclick="return confirm('確定？')">儲存</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
