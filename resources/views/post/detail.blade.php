@extends('layouts.app')

@section('content')
<div class="jumbotron jumbotron-fluid">
    <div class="container">
        <h1>{{ $post->title }}</h1>
        <small>Tanggal: {{ $post->created_at }}</small>
        <p>{{ $post->description }}</p>
        <a href="{{ route('post.edit', $post->id) }}" class="btn btn-primary">Edit</a>
        <form action="{{ route('post.destroy', $post->id) }}" method="POST"
            style="display:inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600
            hover:text-red-800">Delete</button>
        </form>
    </div>
</div>
@endsection