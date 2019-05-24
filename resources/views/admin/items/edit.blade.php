@extends('layouts/main')

@section('content')
    <div class="jumbotron">
        <div class="container">
            <h1>Edit Auction Item</h1>
            <a href="/admin" class="btn btn-secondary" role="button">Return to Auction Management</a>
        </div>
    </div>

    <div class="container">
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

                    <div class="mb-3">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" rows="5" id="description" name="description" id="description">{{ old('description', $item->description) }}</textarea>

                            <input name="description2" type="hidden">
                            {{--}}
                            <div id="editor">
                                <p>Hello World!</p>
                                <p>Some initial <strong>bold</strong> text</p>
                                <p><br></p>
                            </div>--}}

                            {!! fieldErrorMessage('description', $errors) !!}
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="reserve">Reserve Price (optional)</label>
                            <input type="text" class="form-control" name="reserve" placeholder="0" value="{{ old('reserve', $item->reserve) }}">
                        </div>
                    </div>
                </div>

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
            <button class="btn btn-primary btn-lg" type="submit">Save</button>
        </form>
    </div>
@endsection

@section('page-styles')
    <link href="/css/slim.min.css" rel="stylesheet">
@stop

@section('page-scripts')
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="/js/slim.kickstart.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            //var quill = new Quill('#editor', {theme: 'snow'});
            function imageRemoved(data) {
                console.log(data);
                alert('nuked');
            }
        });
    </script>
@endsection
