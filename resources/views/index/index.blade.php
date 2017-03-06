{{-- 引用主体模板 --}}
@extends('public/head')

@section('css')
	<link rel="stylesheet" href="{{ asset('/css/index/index.css')}}">
@endsection

@section('content')
<div class="container">
	<div class="row">
		<div class="col-xs-12">  
			<table class="table  table-bordered table-striped table-hover">
	  			<tr>
	  				<th>工具名称</th>
	  				<th>地址</th>
	  			</tr>
			  	<tr>
			  		<td>网易云音乐</td>
			  		<td>myaddress</td>
			    </tr>
			    <tr>
			  		<td>myname</td>
			  		<td>myaddress</td>
			    </tr>
			    <tr>
			  		<td>myname</td>
			  		<td>myaddress</td>
			    </tr>
  			</table>
  		</div>
  	</div>
</div>
@endsection 
