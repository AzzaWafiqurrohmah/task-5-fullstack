@php
    $article = isset($article) ? $article : null;
@endphp

@csrf
<div class="row mb-3" style="margin-left: 10px; margin-top: 15px">
    <div class="col-sm-4" style="margin-top: 15px">
        <label class="image-preview " for="image" style="background-image: url('{{ Storage::url($article?->image) }}')">
            <small>Click to {{ $article ? 'change' : 'upload' }} a photo</small>
            <input type="file" name="image" id="image" class="d-none @error('image') is-invalid @enderror " accept="image/*">
        </label>
        @error('image')
            <span class="invalid-feedback" role="alert">
                <label for="image">{{$message}}</label>
            </span>
        @enderror
    </div>

    <div class="col-sm-8 mb-2" style="margin-top: 10px">
        <div class="mb-3" style="font-size: 14px;">
            <label for="title" class="form-label">Judul</label>
            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" value="{{ old('title', $article?->title) }}" name="title">
            @error('title')
            <span class="invalid-feedback" role="alert">
                <label for="title">{{$message}}</label>
            </span>
        @enderror
        </div>

        <div class="mb-3" style="font-size: 14px; display: flex; flex-direction: column;">
            <label for="category_id" class="form-label" style="margin-bottom: 5px;">Katagori</label>
            <div class="row" style="display: flex; margin-left: 0px; margin-right: 0px">
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" aria-label="Pilih Kategori" id="category_id" style="width: 100%; margin-right: 10px;">
                    <option>-- Pilih --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @if($category->id == $article?->category->id) selected @endif>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <span class="invalid-feedback" role="alert">
                        <label for="category_id">{{$message}}</label>
                    </span>
                @enderror
            </div>
        </div>
        <div class="form-floating">
            <textarea class="form-control @error('content') is-invalid @enderror"  id="content" name="content" style="height: 120px">{{ $article? $article->content : '' }}</textarea>
            @error('content')
                <span class="invalid-feedback" role="alert">
                    <label for="content">{{$message}}</label>
                </span>
            @enderror
        </div>
    </div>


    <div style=" text-align: end;">
        <button  class="btn btn-primary"  style="font-size: 12px;" id="btn-submit" name="btn-submit" >Simpan</button>
    </div>
</div>

@push('script')
    <script>
        $('#image').on('change', function() {
            const preview = $(this).parent();
            const file = this.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.css('background-image', `url('${e.target.result}')`);
            }

            reader.readAsDataURL(file);
        });
    </script>
@endpush