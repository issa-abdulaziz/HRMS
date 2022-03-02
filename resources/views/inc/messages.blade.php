@if(count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger alert-dismissable m-3">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{$error}}
        </div>
    @endforeach
@endif
@if (session('success'))
    <div class="alert alert-success m-3" id="alert-success">
        {{session('success')}}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissable m-3">
        {{session('error')}}
        <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
@endif