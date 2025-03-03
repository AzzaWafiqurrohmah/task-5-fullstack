@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Article') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <section class="section dashboard" id="article">
                        <!-- Search Bar -->
                        <div class="search-bar-custom d-flex col-sm-10" style="display: block; margin-bottom: 20px;">
                            <form class="search-form-custom d-flex align-items-center w-50" method="GET" action="#">
                                <input type="text" name="search" id="search" class="form-control me-2" placeholder="Search article ...">
                            </form>
                            <div class="d-flex align-items-center">
                                <button class="btn btn-success w-100" id="newArticle" name="newArticle">New Article</button>
                            </div>
                        </div>

                        <!-- Article List -->
                        <div class="row article gx-2 gy-3" id="articles">
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@include('components.article_js')