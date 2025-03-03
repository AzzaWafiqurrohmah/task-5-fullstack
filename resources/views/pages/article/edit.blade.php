@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Update Article') }}</div>

                <div class="card-body">

                    <section class="section dashboard" id="updateArticle">
                        <form action="{{ route('articles.update', $article->id) }}" id="editForm" enctype="multipart/form-data" method="post">
                            @method('PUT')
                            @include('pages.article.form')
                        </form>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection