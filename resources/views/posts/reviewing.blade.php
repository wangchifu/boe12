@extends('layouts.app')

@section('title','公告作業區 | ')

@section('page-style')
    <style>
        DIV.table {
            display: table;
        }

        FORM.tr, DIV.tr {
            display: table-row;
        }

        SPAN.td {
            display: table-cell;
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="py-5">
            <div class="">
                @include('posts.nav')
                @if(!empty(auth()->user()->section_id))
                    <div class="card my-4">
                        <div class="card-header text-center">
                            <h3 class="py-2">
                                公告系統：個人 <span class="badge bg-warning"><i class="fas fa-exclamation-circle"></i> 作業區</span>
                            </h3>
                        </div>
                        <div class="card-body">
                            @include('posts.list')
                        </div>
                        <div class="card-footer d-flex flex-row justify-content-center pt-4">
                            {{ $posts->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
