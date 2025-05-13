@extends('layouts.app_print')

@section('title','未簽收公告 | ')

@section('content')
        <style>
                table {
                    border: 2px solid ; border-collapse: collapse;
                    margin: 20px;
                    }
                tr,th,td {
                     border: 2px solid ;
                    }
                th,td{
                     padding: 10px;
                    }
        </style>
        <div class="container-fluid">
                <div class="row">
                        <table class="col-11">
                                <tr>
                                        <th nowrap width="100">
                                                負責處室
                                            </th>
                                        <th nowrap width="100">
                                                公告編號
                                            </th>
                                        <th>
                                                行政公告主旨
                                            </th>
                                        <th nowrap width="120">
                                                發佈人
                                            </th>
                                        <th nowrap width="100">
                                                公告日期
                                            </th>
                                    </tr>
                                @foreach($posts as $post)
                                        <tr>
                                                <td nowrap>
                                                        教務 學務<br>
                                                        總務 輔導
                                                    </td>
                                                <td nowrap>
                                                        {{ $post->post_no }}
                                                    </td>
                                                <td>
                                                        <strong>[行政公告]</strong> {{ $post->title }}
                                                    </td>
                                                <td nowrap>
                                                        {{ array_get($sections,$post->section_id) }} / {{ $post -> name }}
                                                    </td>
                                                <td nowrap>
                                                        {{ substr($post->passed_at,0,10) }}
                                                    </td>
                                            </tr>
                                    @endforeach
                            </table>
                    </div>
@endsection
