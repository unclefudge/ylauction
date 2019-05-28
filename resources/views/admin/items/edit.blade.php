@extends('layouts/main')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <br>
                <a href="/admin" class="btn btn-outline-primary float-right"><i class="fa fa-arrow-left"></i> Return to Auction Management</a>
            </div>
        </div>
        <form method="POST" action="/admin/item/{{ $item->id }}" enctype="multipart/form-data">
            {{ method_field('PATCH') }}
            {{ csrf_field() }}
            <input name="previous_image1" type="hidden" value="{{ $item->image1 }}">
            <input name="previous_image2" type="hidden" value="{{ $item->image2 }}">
            <input name="previous_image3" type="hidden" value="{{ $item->image3 }}">
            <input name="previous_image4" type="hidden" value="{{ $item->image4 }}">

            @include('form-error')
            <div class="row">
                {{-- Photos --}}
                <div class="col-md-8">
                    <h4 class="mb-3">Item Details</h4>
                    <div class="mb-3">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" name="name" placeholder="" value="{{ old('name', $item->name) }}">
                        {!! fieldErrorMessage('name', $errors) !!}
                    </div>

                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="donated_by">Donated By</label>
                            <input type="text" class="form-control" name="donated_by" placeholder="" value="{{ old('donated_by', $item->donated_by) }}">
                            {!! fieldErrorMessage('donated_by', $errors) !!}
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="reserve">Reserve Price (optional)</label>
                            <input type="text" class="form-control" name="reserve" placeholder="0" value="{{ old('reserve', $item->reserve) }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="brief">Brief Description (100 chars)</label>
                        <input type="text" class="form-control" name="brief" placeholder="" value="{{ old('brief', $item->brief) }}">
                        {!! fieldErrorMessage('brief', $errors) !!}
                    </div>

                    <div class="mb-3">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <input name="description" type="hidden">
                            <div id="quill_description" style="background: #fff; height: 300px">{!!  old('description', $item->description) !!}</div>
                            {!! fieldErrorMessage('description', $errors) !!}
                        </div>
                    </div>
                </div>

                {{-- Images --}}
                <div class="col-md-4">
                    <h4 class="mb-3"><span class="text-muted">Images</span></h4>
                    <span class="{!! fieldHasError('image1', $errors) !!}" style="font-size: 16px">{!! fieldErrorMessage('image1', $errors) !!}</span>
                    <div class="row" style="padding: 10px">
                        <div class="col-6">
                            <div class="slim {!! fieldHasError('image1', $errors) !!}" data-ratio="1:1" data-push=true data-size="400,400" data-label="<i class='fa fa-camera fa-3x'></i><br>Add Photo">
                                <input type="file" name="image1" value="{{ $item->image1 }}">
                                @if ($item->image1)
                                    <img src="/auction/images/{{ $item->image1 }}">
                                @endif
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="slim" data-ratio="1:1" data-push=true data-size="400,400" data-did-remove="imageRemoved" data-label="<i class='fa fa-camera fa-3x'></i><br>Add Photo">
                                <input type="file" name="image2">
                                @if ($item->image2)
                                    <img src="/auction/images/{{ $item->image2 }}">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row" style="padding: 10px">
                        <div class="col-6">
                            <div class="slim" data-ratio="1:1" data-push=true data-size="400,400" data-label="<i class='fa fa-camera fa-3x'></i><br>Add Photo">
                                @if ($item->image3)
                                    <img src="/auction/images/{{ $item->image3 }}">
                                @endif
                                    <input type="file" name="image3">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="slim" data-ratio="1:1" data-push=true data-size="400,400" data-label="<i class='fa fa-camera fa-3x'></i><br>Add Photo">
                                @if ($item->image4)
                                    <img src="/auction/images/{{ $item->image4 }}">
                                @endif
                                    <input type="file" name="image4">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="mb-4">
            <a href="/admin/item/{{ $item->id }}" class="btn btn-light btn-lg mr-3" type="submit">Cancel</a>
            <button class="btn btn-primary btn-lg" type="submit">Save</button>
            <br><br>
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
                        [{ header: [1, 2, false] }],
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
                form.submit();
                return false;
            };

            function imageRemoved(data) {
                console.log(data);
                alert('nuked');
            }
        });
    </script>
@endsection
