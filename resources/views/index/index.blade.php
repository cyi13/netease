{{-- 引用主体模板 --}}
@extends('public/head')

@section('css')
	<link rel="stylesheet" href="{{asset('/css/index/index.css')}}">
@endsection

@section('content')
<div class="site-header jumbotron">
		<div class="container">
				<div class="row">
						<div class="col-xs-12">
								<h1>FUNCTION</h1>
								<p>一些小工具，仅供学习使用<br></p><br/><br/>
								<form class="" role="search">
										<div class="form-group">
											<input type="text" class="form-control search clearable" placeholder="搜索工具名称，如：计算器">
											<span class="fa fa-search"></span>
										</div>
								</form>
						</div>
				</div>
		</div>
</div>
<div class="site-body">
		<div class="container">
				<div class="row">
						<div class="col-xs-12">  
								<table class="table  table-bordered table-striped table-hover">
									<tr>
										<th>工具名称</th>
										<th>地址</th>
									</tr>
									@foreach($list as $value)
									<tr>
										<td>{{ $value->functionName }}</td>
										<td><a href="http://{{ $value->functionAddress }}">{{ $value->functionAddress }}</a></td>
									</tr>
									@endforeach
									</table>
							</div>
				</div>
		</div>
</div>
@endsection 
