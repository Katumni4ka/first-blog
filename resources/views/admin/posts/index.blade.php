@extends('admin.layout')

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Blank page
                <small>it all starts here</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                <li><a href="#">Examples</a></li>
                <li class="active">Blank page</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            @csrf
            <!-- Default box -->
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">ПОСТЫ</h3>
                    @include('admin.errors')
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group">
                        <a href="{{route('posts.create')}}" class="btn btn-success">Добавить</a>
                    </div>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Теги</th>
                            <th>Картинка</th>
                            <th>Публикация</th>
                            <th>Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($posts as $post)
                        <tr>
                            <td>{{$post->id}}</td>
                            <td>{{$post->title}}</td>
                            <td>{{$post->getCategoryTitle()}}</td>
                            <td>{{$post->getTagsTitles()}}</td>
                            <td>
                                <img src="{{$post->getImage()}}" alt="" width="100">
                            </td>
                            <td>
                            @if($post->status === \App\Models\Post::STATUS_IS_PUBLIC)
                                <input type="checkbox" name="post_status" checked>
                            @else
                                <input type="checkbox" name="post_status">
                            @endif
                                {{Form::open(['route'=>['posts.toggleStatus', $post->id], 'method'=>'POST'])}}
                                {{ Form::checkbox(
                                    'checkbox_post_status',
                                    $post->status,
                                    $post->status === \App\Models\Post::STATUS_IS_PUBLIC,
                                    [
                                        'onchange' => 'formSubmit($(this))'
                                    ]
                                ) }}
                                {{Form::close()}}
                            </td>
                            <td>
                                <a href="{{route('posts.edit', $post->id)}}" class="fa fa-pencil"></a>
                                {{Form::open(['route'=>['posts.destroy', $post->id], 'method'=>'delete'])}}
{{--                                @method('delete')--}}
                                    <button onclick="return confirm ('Are you sure?')" class="delete">
                                        <i class="fa fa-remove"></i>
                                    </button>
                                {{Form::close()}}
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection
<script>
    function toggleStatus(jQueryelement) {
        var url = jQueryelement.data('url')
        var token = $('input[name=_token]')
        console.log(token)
        $.ajax({
                url: url,
                type: "POST",
                success: function (response) {
                    console.log(response)
                }
            }
        )
    }

    console.log({'g': this})
    function formSubmit($checkbox) {
        console.log({'l': this})

        // console.log($checkbox)
        // $checkbox.closest('form').submit()
    }
</script>
