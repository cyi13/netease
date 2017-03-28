@extends('public/head')
@section('css')
    <link href="{{ asset('/css/crawlers/style.css')}}" rel='stylesheet'>
@endsection
@section('content')
    <div class='index-body'>
        <div class='container-fluid'>
            <div class='row'>
                <div class='col-xs-12'>
                    <h3>网易云音乐评论数大于一万的歌曲</h3>
                </div>
            </div>
            <div class="row search-field">
                <div class="col-xs-2 col-md-offset-1">
                    <from id="auto-form" name="form" action="{{ route('cloudMusicPage') }}">
                        <div class="input-group">
                            <div class="input-group-btn">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span id="search-title">歌曲名</span>
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="search-menu" href="javascript:void(0);" data-name="musicTitle">歌曲名</a></li>
                                    <li><a class="search-menu" href="javascript:void(0);" data-name="singerMessage">歌手</a></li>
                                </ul>
                            </div>
                             <input type="text" name="musicTitle" id="search-input" class="form-control" aria-describedby="sizing-addon1">
                            <span class="input-group-btn">
                                <button class="btn btn-default" id="submit-button" type="submit" style="width: 100%;height: 100%"><i class="fa fa-search"></i></button>
                                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            </span>
                        </div>
                    </from>
                </div>
            </div>
            <div class='row table-field'>
                <div class='col-xs-12 col-md-offset-1'>
                    <table class='table table-striped table-hover'>
                        <thead>
                            <tr>
                                <th style="width:3%">#</th>
                                <th>标题</th>
                                <th>演唱者</th>
                                <th>专辑</th>
                                <th>评论数</th>
                                {{--<th style="width:27%">链接地址</th>--}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($musicList as $list)
                            <tr>
                                <td>{{ $loop->iteration  }}</td>
                                <td><a href="{{ $list->link }}" target="_blank" title="{{ $list->musicTitle }}"> {{ $list->musicTitle }}</a></td>
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
                                {{--<td>--}}
                                    {{--<a href="{{ $list->link }}" target='_blank'>{{ $list->link }}</a>--}}
                                {{--</td>--}}
                            </tr>   
                            @endforeach   
                        </tbody>                                     
                    </table>
                </div>
            </div> 
        </div>
        @if($totalPageNum > 1)
         <input id="pageUrl" type="hidden" data-url="{{ route('cloudMusicPage') }}" data-token="{{ csrf_token() }}" data-max="{{ $totalPageNum }}"/>
        <div class='row'>
            <div class='col-xs-12 col-md-offset-1'>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li>
                        <a class="previous" href="javascript:void(0);" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                        </li>
                        @for($i=1;$i <=  $totalPageNum;$i++)
                            @if($i > 13)
                                <li><a class="bypass" href="javascript:void(0);">...</a></li>
                                <li><a class="page lastPage" href="javascript:void(0);">{{ $totalPageNum }}</a></li>
                                @break;
                            @elseif($i==1)
                                <li class="active"><a class="page firstPage" href="javascript:void(0);">{{ $i }}</a></li>
                            @else
                                <li><a class="page" href="javascript:void(0);">{{ $i }}</a></li>
                            @endif
                            
                        @endfor 
                        <li>
                        <a class="next" href="javascript:void(0);" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>w
    @endif
@endsection

@section('javascript')
    <script>
        $(function(){
            $('.search-menu').click(function(){
                var name  = $(this).attr('data-name');
                var title = $(this).text();
                $('#search-input').prop('name',name);
                $('#search-title').text(title);
            });
            $('#submit-button').click(function() {
                $('#auto-form').submit();
            });
            $('#auto-form').submit(function(){
                var url         = $(this).attr('action');
                var name        = $('#search-input').val();
                console.log(name);
                var token       = $('input[name="_token"]').eq(0).val();
                var searchName  = $('#search-input').prop('name');
                var data        = {name:name,'searchName':searchName,'_token':token};
                reCratePage(url,data);
                return false;
            })

            //作为全局变量
            var maxPageNum   = parseInt($('#pageUrl').attr('data-max'));
            var pageShowNum  = 15;
            //跳转页数
            $(document).on('click','.page',function(){
                var pageNum = parseInt($(this).text());
                changePage(pageNum);
                if(pageNum == maxPageNum || pageNum == 1){
                    switch(pageNum){
                        case 1:
                            recreatePage(1,1,'prev');
                            break;
                        case maxPageNum:
                            recreatePage(pageNum,maxPageNum,'next');
                            break;

                    }
                    $.each($('.pagination li a'),function(k,v){
                        var nowPage = parseInt($(this).text());
                        if(nowPage == pageNum){
                            nextObject = $(this).parent();
                        }
                    });
                    changeCurrentPosition(nextObject);
                }else{
                    changeCurrentPosition($(this).parent());
                }
               
            });
            //上一页
            $(document).on('click','.previous',function(){
                var currentPageNum = parseInt($('.active a').text());
                if(currentPageNum == 1){
                    return false;
                }
                var previousPageNum = currentPageNum - 1;
                changePage(previousPageNum);
               //判断显示的页数是否需要重新生成
                var nextObject = $('.active').prev();
                if($('.active').prev().find('a').hasClass('bypass')){
                    recreatePage(currentPageNum,previousPageNum,'prev');
                    $.each($('.pagination li a'),function(k,v){
                        var nowPage = parseInt($(this).text());
                        if(nowPage == previousPageNum){
                            nextObject = $(this).parent();
                        }
                    })
                }
                changeCurrentPosition(nextObject);
            });
            //下一页
            $(document).on('click','.next',function(){
            // $('.next').on('click',function(){
                var largePageNum   = parseInt($('.next').parent().prev().find('a').text());
                var currentPageNum = parseInt($('.active a').text());
                if(currentPageNum == largePageNum){
                    return false;
                }
                var nextPageNum = currentPageNum + 1; 
                changePage(nextPageNum);
                //判断显示到页数是否需要重新生成
                var nextObject = $('.active').next();
                if($('.active').next().find('a').hasClass('bypass') || $(this).text() == 15){
                    recreatePage(currentPageNum,nextPageNum,'next');
                    $.each($('.pagination li a'),function(k,v){
                        var nowPage = parseInt($(this).text());
                        if(nowPage == nextPageNum){
                            nextObject = $(this).parent();
                        }
                    })
                }
                changeCurrentPosition(nextObject);
            });
            //重新生成分页页数
            function recreatePage(currentPageNum,nextPageNum,action){
                //生成页数
                console.log(nextPageNum+'-'+action);
                $('.pagination').empty();
                var pageArea = '<li><a class="previous" href="javascript:void(0);" aria-label="Previous">'
                            +'<span aria-hidden="true">&laquo;</span></a></li>';
                //总共pageShowNum个位置  17 除上一页和下一页
                pageArea += '<li><a class="page firstPage" href="javascript:void(0);">1</a></li>';
                var remainPageNum = pageShowNum-2;
                if(nextPageNum <= remainPageNum && action == 'prev'){
                    if(maxPageNum > remainPageNum){
                        for(var i=2;i<=remainPageNum;i++){
                            pageArea += '<li><a class="page" href="javascript:void(0);">'+ i +'</a></li>';
                        }
                        pageArea += '<li><a class="bypass" href="javascript:void(0);">...</a></li>';
                    }else{
                        //如果总页数小于指定的页数 并且是点击第一页
                        for(var i=2;i<maxPageNum;i++){
                            pageArea += '<li><a class="page" href="javascript:void(0);">'+ i +'</a></li>';
                        }
                    }
                }else if(nextPageNum >= maxPageNum-remainPageNum){
                    if(maxPageNum-remainPageNum >= 0){
                        pageArea += '<li><a class="bypass" href="javascript:void(0);">...</a></li>';
                        for(var i=maxPageNum-remainPageNum+1;i<maxPageNum;i++){
                            pageArea += '<li><a class="page" href="javascript:void(0);">'+ i +'</a></li>';
                        }
                    }else {
                        //如果总页数小于指定的页数 并且是点击最后一页
                        for(var i=2;i<maxPageNum;i++){
                            pageArea += '<li><a class="page" href="javascript:void(0);">'+ i +'</a></li>';
                        }
                    }
                }else{
                    remainPageNum -= 2;
                    pageArea += '<li><a class="bypass" href="javascript:void(0);">...</a></li>';
                    switch(action){
                        case 'prev':
                            var startPageNum = nextPageNum-remainPageNum;
                            var endPageNum   = nextPageNum;
                            break;
                        case 'next':
                            var startPageNum = nextPageNum;
                            var endPageNum   = nextPageNum+remainPageNum;
                            break;
                    }
                    for(var i = startPageNum;i<=endPageNum;i++){
                        pageArea += '<li><a class="page" href="javascript:void(0);">'+ i +'</a></li>';
                    }
                    pageArea += '<li><a class="bypass" href="javascript:void(0);">...</a></li>';
                                       
                }
                pageArea += '<li><a class="page lastPage" href="javascript:void(0);">'+ maxPageNum +'</a></li>';
                pageArea += '<li><a class="next" href="javascript:void(0);" aria-label="Next">'
                             +'<span aria-hidden="true">&raquo;</span></a></li>';
                 $('.pagination').append(pageArea);
            }
            //改变a标签激活的位置
            function changeCurrentPosition($obj){
                $('.active').removeClass('active');
                $obj.addClass('active');
            }
            //根据页数改变显示的内容
            function changePage(pageNum){
                var url = $('#pageUrl').attr('data-url');
                var token = $('#pageUrl').attr('data-token');
                var data   = {'pageNum':pageNum,'_token':token};
                reCratePage(url,data);
            }
            //重新生成界面
            function reCratePage(url,data){
                $.post(url,data,function(data){
                    $('.table tbody').empty();
                    $.each(data,function(key,value){
                        var tr                  = $('<tr></tr>');
                        var keyArea             = '<td>'+ (key+1) +'</td>';
                        var musicTitleArea      = '<td><a href="'+ value.link +'" target="_blank" title="'+ value.musicTitle +'">'+ value.musicTitle +'</a></td>';
//                        var musicTitleArea      = '<td>'+ value.musicTitle +'</td>';
                        var musicAlbumTitleArea = '<td><a href="'+ value.musicAlbumLink +'" target="_blank"'
                            +'title="'+ value.musicAlbumTitle +'">'+ value.musicAlbumTitle + '</td>';
                        var commentArea         = '<td>'+ value.totalComment +'</td>';
                                {{--<td><a href="{{ $list->link }}" target="_blank" title="{{ $list->musicTitle }}"> {{ $list->musicTitle }}</a></td>--}}
                        var singleArea          = '<td>';
                        var length              = value.singerMessage.length;
                        $.each(value.singerMessage,function(k,v){
                            if(k == length-1){
                                singleArea += '<a href="'+v.singerLink+'" target="_blank" title="'+v.singer+'">'+v.singer+'</a>';
                            }else{
                                singleArea += '<a href="'+v.singerLink+'" target="_blank" title="'+v.singer+'">'+v.singer+'</a>/';
                            }
                        });
                        tr.append(keyArea+musicTitleArea+singleArea+musicAlbumTitleArea+commentArea);
                        $('.table tbody').append(tr);
                    })
                },'json');
            }
        })
    </script>
@endsection