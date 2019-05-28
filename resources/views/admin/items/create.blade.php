@extends('layouts/main')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h1>Add Auction Item</h1>
            <a href="/admin" class="btn btn-secondary" role="button">Return to Auction Management</a>
        </div>
    </div>

    <div class="container">
        <form method="POST" action="/admin/item" enctype="multipart/form-data">
            @include('form-error')
            {{ csrf_field() }}
            <div class="row">
                {{-- Photos --}}
                <div class="col-md-8">
                    <h4 class="mb-3">Item Details</h4>
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="" value="{{ old('name') }}">
                        {!! fieldErrorMessage('name', $errors) !!}
                    </div>
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="donated_by">Donated By</label>
                            <input type="text" class="form-control" name="donated_by" placeholder="" value="{{ old('donated_by') }}">
                            {!! fieldErrorMessage('donated_by', $errors) !!}
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="reserve">Reserve Price (optional)</label>
                            <input type="text" class="form-control" name="reserve" placeholder="0" value="{{ old('reserve', 0) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="brief">Brief Description (100 chars)</label>
                        <input type="text" class="form-control" name="brief" placeholder="" value="{{ old('donated_by') }}">
                        {!! fieldErrorMessage('brief', $errors) !!}
                    </div>

                    <div class="mb-3">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input name="description" type="hidden">
                            <div id="quill_description" style="background: #fff; height: 300px"></div>

                            {!! fieldErrorMessage('description', $errors) !!}
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <h4 class="mb-3"><span class="text-muted">Images</span></h4>
                    <span class="{!! fieldHasError('image1', $errors) !!}" style="font-size: 16px">{!! fieldErrorMessage('image1', $errors) !!}</span>
                    <div class="row" style="padding: 10px">
                        <div class="col-6">
                            <div class="slim {!! fieldHasError('image1', $errors) !!}" data-ratio="1:1" data-push=true data-size="400,400" data-label="<i class='fa fa-camera fa-3x'></i><br>Add Photo">
                                <input type="file" name="image1">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="slim" data-ratio="1:1" data-push=true data-size="400,400" data-label="<i class='fa fa-camera fa-3x'></i><br>Add Photo">
                                <input type="file" name="image2">
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding: 10px">
                        <div class="col-6">
                            <div class="slim" data-ratio="1:1" data-push=true data-size="400,400" data-label="<i class='fa fa-camera fa-3x'></i><br>Add Photo">
                                <input type="file" name="image3">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="slim" data-ratio="1:1" data-push=true data-size="400,400" data-label="<i class='fa fa-camera fa-3x'></i><br>Add Photo">
                                <input type="file" name="image4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <button class="btn btn-primary btn-lg" type="submit">Create</button>
        </form>
    </div>
@endsection

@section('page-styles')
    <link href="/css/slim.min.css" rel="stylesheet">
    <link href="/css/quill.snow.css" rel="stylesheet">
@stop

@section('page-scripts')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="/js/slim.kickstart.min.js"></script>
    <script src="/js/quill.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var quill = new Quill('#quill_description', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ header: [1, 2, 3] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'align': [] }, { 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        ['link', 'image', 'clean'],
                    ]
                }
            });

            var form = document.querySelector('form');
            form.onsubmit = function() {
                // Populate hidden form on submit
                var description = document.querySelector('input[name=description]');
                description.value = quill.root.innerHTML;
                //description.value = JSON.stringify(quill.getContents());
                form.submit();
                return false;
            };
        });
    </script>
@endsection
