@extends('layouts.app')
@section('title', 'Post')
@section('content')
 <div class="bg-white rounded-lg shadow-sm p-6 border border-gray-200">
 <h1 class="text-3xl font-bold text-gray-900 mb-4">Post</h1>
 @if ($errors->any())
 @foreach ($errors->all() as $error)
 <div class="bg-red-500 text-white p-4 rounded mb-4">{{ $error
}}</div>
 @endforeach
 @endif
 <form action="{{ route('post.update', $post->id) }}" method="post">
 @csrf
 @method('PUT')
 <div>
 <label for="title">Title</label>
 </div>
 <div>
 <input type="text" name="title" id="title" value="{{ $post->title ?? old('title') }}" required class="border border-gray-300 p-2 rounded w-full">
    @error('title')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
 </div>
 <div>
 <label for="description">Description</label>
 </div>
 <div>
 <textarea name="description" id="description" class="border border-gray-300 p-2 rounded w-full"
 required>{{ $post->description ?? old('description')
}}</textarea>
    @error('description')
    <div class="invalid-feedback">{{ $message }}</div>
    @enderror
 </div>
 <button type="submit"
 class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2
px-4 rounded">Submit</button>
 </form>
 </div>
@endsection