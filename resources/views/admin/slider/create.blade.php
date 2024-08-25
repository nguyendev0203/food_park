@extends('admin.layouts.master')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Slider</h1>
    </div>
    <div class="card card-primary">
        <div class="card-header">
            <h4>Create Slider</h4>
            
        </div>
        <div class="card-body">
            <form action="{{route('admin.slider.store')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Image</label>
                    <div id="image-preview" class="image-preview">
                        <label for="image-upload" id="image-label">Choose File</label>
                        <input type="file" name="image" id="image-upload">
                    </div>
                </div>
                <div class="form-group">
                    <label>Offer</label>
                    <input type="text" class="form-control" name="offer">
                </div>
                <div class="form-group">
                    <label>Title</label>
                    <input type="text" class="form-control" name="title">
                </div>
                <div class="form-group">
                    <label>Sub Title</label>
                    <input type="text" class="form-control" name="sub_title">
                </div>
                <div class="form-group">
                    <label>Short Description</label>
                    <textarea type="text" class="form-control" name="short_description"></textarea>
                </div>
                <div class="form-group">
                    <label>Button Link</label>
                    <input type="text" class="form-control" name="button_link">
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" name="status">
                        <option value="1">Enable</option>
                        <option value="0">Disable</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>

</section>
@endsection
