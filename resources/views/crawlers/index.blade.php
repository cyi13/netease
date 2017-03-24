@extends('public/head')
@section('css')
    <link href="{{ asset('/css/crawlers/style.css')}}" rel='stylesheet'>
@endsection
@section('content')
    <div class='index-body'>
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-xs-12'>
                    <h2>测试字体非常大</h2>
                </div>
            </div>
            <div class='row table-field'>
                <div class='col-xs-12 col-md-offset-1'>
                    <table class='table table-striped table-hover'>
                        <tr>
                            <th style="width:5%">#</th>
                            <th style="width:15%">标题</th>
                            <th style="width:25%">演唱者</th>
                            <th style="width:20%">专辑</th>
                            <th style="width:10%">评论数</th>
                            <th style="width:25%">链接地址</th>
                        </tr>
                        @foreach($musicList as $list)
                        <tr>
                            <td>{{ $loop->iteration  }}</td>
                            <td>{{ $list->musicTitle }}</td>
                            <td>
                             @foreach($list->singerMessage as $singerMessage)   
                                <a href="{{ $singerMessage->singerLink }}" target="_blank" title="{{ $singerMessage->singer }}">
                                    {{$singerMessage->singer}}
                                    @if($loop->remaining != 0) / @endif
                                </a>
                             @endforeach                            
                            </td>
                            <td>
                                <a href="{{ $list->musicAlbumLink}}" target='_blank' title="{{ $list->musicAlbumTitle }}">
                                    {{ $list->musicAlbumTitle }}
                                </a>
                            </td>
                            <td>{{ $list->totalComment }}</td>
                            <td>
                                <a href="{{ $list->link }}">{{ $list->link }}</a>
                            </td>
                        </tr>   
                        @endforeach                                        
                    </table>
                </div>
            </div> 
        </div>
        <div class='row'>
            <div class='col-xs-12 col-md-offset-1'>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li>
                        <a href="#" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                        </li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li>
                        <a href="#" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
@endsection