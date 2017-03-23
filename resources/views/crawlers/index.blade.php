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
                            <th style="width:20%">标题</th>
                            <th style="width:20%">演唱者</th>
                            <th style="width:20%">评论数</th>
                            <th style="width:35%">链接地址</th>
                        </tr>
                        <tr>
                            <td>1</td>
                            <td>告白气球</td>
                            <td>周杰伦</td>
                            <td>10000</td>
                            <td>http://www.baidu.com</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>告白气球</td>
                            <td>http://www.baidu.com</td>
                            <td>周杰伦</td>
                            <td>10000</td>
                        </tr>   
                        <tr>
                            <td>3</td>
                            <td>告白气球</td>
                            <td>http://www.baidu.com</td>
                            <td>周杰伦</td>
                            <td>10000</td>
                        </tr>                                             
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