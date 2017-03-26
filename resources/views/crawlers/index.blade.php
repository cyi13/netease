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
                        <thead>
                            <tr>
                                <th style="width:3%">#</th>
                                <th style="width:15%">标题</th>
                                <th style="width:25%">演唱者</th>
                                <th style="width:20%">专辑</th>
                                <th style="width:10%">评论数</th>
                                <th style="width:27%">链接地址</th>
                            </tr>
                        </thead>
                        <tbody>
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
                                    <a href="{{ $list->link }}" target='_blank'>{{ $list->link }}</a>
                                </td>
                            </tr>   
                            @endforeach   
                        </tbody>                                     
                    </table>
                </div>
            </div> 
        </div>
        @if($totalPageNum > 1)
        <input id="pageUrl" type="hidden" data-url="{{ route('cloudMusicPage') }}" data-token="{{ csrf_token() }}" />
        <div class='row'>
            <div class='col-xs-12 col-md-offset-1'>
                <nav aria-label="Page navigation">
                    <ul class="pagination">
                        <li>
                        <a class="previous" href="javascript::void();" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                        </li>
                        @for($i=1;$i <  $totalPageNum;$i++)
                            @if($i > 15)
                                <li><a class="bypass" href="javascript::void();">...</a></li>
                                <li><a class="page lastPage" href="javascript::void();">{{ $totalPageNum }}</a></li>
                                @break;
                            @elseif($i==1)
                                <li class="active"><a class="page firstPage" href="javascript::void();">{{ $i }}</a></li>
                            @else
                                <li><a class="page" href="javascript::void();">{{ $i }}</a></li>
                            @endif
                            
                        @endfor 
                        <li>
                        <a class="next" href="javascript::void();" aria-label="Next">
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
            //作为全局变量
            var maxPageNum   = parseInt($('.lastPage').text());
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
            //首页

            //末页

            //重新生成分页页数
            function recreatePage(currentPageNum,nextPageNum,action){
                //生成页数
                var pageNumArray = new Array();
                $('.pagination').empty();
                var pageArea = '<li><a class="previous" href="javascript::void();" aria-label="Previous">'
                            +'<span aria-hidden="true">&laquo;</span></a></li>';
                pageArea += '<li><a class="page firstPage" href="javascript::void();">1</a></li>';
                //总共17个位置
                if(nextPageNum <= 15 && action=='prev'){
                    for(var i=2;i<=15;i++){
                        pageArea += '<li><a class="page" href="javascript::void();">'+ i +'</a></li>';
                    }    
                    pageArea += '<li><a class="bypass" href="javascript::void();">...</a></li>';  
                }else if(nextPageNum >= maxPageNum-15){
                    pageArea += '<li><a class="bypass" href="javascript::void();">...</a></li>';                               
                    for(var i=maxPageNum-15;i<maxPageNum;i++){
                        pageArea += '<li><a class="page" href="javascript::void();">'+ i +'</a></li>';
                    }                     
                }else{
                    
                    pageArea += '<li><a class="bypass" href="javascript::void();">...</a></li>';
                    switch(action){
                        case 'prev':
                            var startPageNum = nextPageNum-13;
                            var endPageNum   = nextPageNum;
                            break;
                        case 'next':
                            var startPageNum = nextPageNum;
                            var endPageNum   = nextPageNum+13;
                            break;
                    }
                    for(var i = startPageNum;i<=endPageNum;i++){
                        pageArea += '<li><a class="page" href="javascript::void();">'+ i +'</a></li>';
                    }
                    pageArea += '<li><a class="bypass" href="javascript::void();">...</a></li>';  
                                       
                }
                pageArea += '<li><a class="page lastPage" href="javascript::void();">'+ maxPageNum +'</a></li>'; 
                pageArea += '<li><a class="next" href="javascript::void();" aria-label="Next">'
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
                $.post(url,data,function(data){
                    $('.table tbody').empty();
                    $.each(data,function(key,value){
                        var tr                  = $('<tr></tr>');
                        var keyArea             = '<td>'+ (key+1) +'</td>';
                        var musicTitleArea      = '<td>'+ value.musicTitle +'</td>';
                        var musicAlbumTitleArea = '<td><a href="'+ value.musicAlbumLink +'" target="_blank"'
                                                  +'title="'+ value.musicAlbumTitle +'">'+ value.musicAlbumTitle + '</td>';
                        var commentArea         = '<td>'+ value.totalComment +'</td>';
                        var linkArea            = '<td><a href="'+ value.link +'" target="_blank" title="'+ value.musicTitle +'">'
                                                  + value.link+'</a></td>';
                        var singleArea          = '<td>';
                        var length              = value.singerMessage.length;
                        $.each(value.singerMessage,function(k,v){
                            if(k == length-1){
                                singleArea += '<a href="'+v.singerLink+'" target="_blank" title="'+v.singer+'">'+v.singer+'</a>';
                            }else{
                                singleArea += '<a href="'+v.singerLink+'" target="_blank" title="'+v.singer+'">'+v.singer+'</a>/';
                            }
                        });
                        tr.append(keyArea+musicTitleArea+singleArea+musicAlbumTitleArea+commentArea+linkArea);
                        $('.table tbody').append(tr);
                    })
                },'json');
            }
        })
    </script>
@endsection