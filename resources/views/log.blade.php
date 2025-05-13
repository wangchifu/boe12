@extends('layouts.app')

@section('title','log | ')

@section('content')
<div class="container">
    <div class="py-5">
        <div class="card">
            <div class="card-header text-center">
                <h3 class="py-2">
                    Log
                </h3>
            </div>
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-md-8">
                            <?php
                            $select_all = ($select_level=="all")?"selected":null;
                            $select[0] = ($select_level==0 and $select_level <> "all")?"selected":null;
                            $select[1] = ($select_level==1)?"selected":null;
                            $select[2] = ($select_level==2)?"selected":null;
                            $select[3] = ($select_level==3)?"selected":null;
                            $select[4] = ($select_level==4)?"selected":null;
                            $select[5] = ($select_level==5)?"selected":null;
                            $select[6] = ($select_level==6)?"selected":null;
                            ?>
                            <form id="level_form" method="get">
                                <select class="form-control" name="select_level" onchange="go()">
                                    <option value="all" {{ $select_all }}>--請選擇--</option>
                                    @foreach($level_array as $k=>$v)
                                        <option value="{{ $k }}" {{ $select[$k] }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </form>
                            <table class="table table-striped">
                                <thead class="thead-light">
                                <tr>
                                    <th nowrap>
                                        等級
                                    </th>
                                    <th nowrap>
                                        時間
                                    </th>
                                    <th nowrap>
                                        訊息
                                    </th>
                                    <th nowrap>
                                        IP
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($logs as $log)
                                    <tr style="word-break: break-all;">
                                        <td>
                                            {{ $level_array[$log->level] }}
                                        </td>
                                        <td>
                                            {{ $log->created_at }}
                                        </td>
                                        <td>
                                            {{ $log->event }}
                                        </td>
                                        <td>
                                            {{ $log->ip }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <small>
                                {{ $logs->appends(['select_level'=>$select_level])->links() }}
                            </small>
                        </div>
                        <div class="col-xs-12 col-md-4">
                            <table class="table table-striped">
                                <thead class="thead-light">
                                <tr>
                                    <th nowrap>
                                        等級
                                    </th>
                                    <th nowrap>
                                        名稱
                                    </th>
                                    <th nowrap>
                                        說明
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        0
                                    </td>
                                    <td>
                                        EMERG
                                    </td>
                                    <td>
                                        系統即將崩潰不可用
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        1
                                    </td>
                                    <td>
                                        ALERT
                                    </td>
                                    <td>
                                        警示，因操作錯誤影響系統正常運作。
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        2
                                    </td>
                                    <td>
                                        CRIT
                                    </td>
                                    <td>
                                        嚴重，有資安疑慮或系統不當存取。
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        3
                                    </td>
                                    <td>
                                        ERR
                                    </td>
                                    <td>
                                        操作錯誤，無法完成指定工作。
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        4
                                    </td>
                                    <td>
                                        WARN
                                    </td>
                                    <td>
                                        更動了系統重要參數。
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        5
                                    </td>
                                    <td>
                                        NOTICE
                                    </td>
                                    <td>
                                        異動了系統核心資料。
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        6
                                    </td>
                                    <td>
                                        INFO
                                    </td>
                                    <td>
                                        系統一般訊息，例使用者登出入資訊。
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<br>
<script>
    function go(){
        $('#level_form').submit();
    }
</script>
@endsection
