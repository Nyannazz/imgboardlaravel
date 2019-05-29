@extends('app')

<style>
    .well>p{
        margin: 0;
        font-size: 0.8rem;
    }
</style>

@section('content')
    
    <h1>Posts</h1>
    <div class="flexGrid">
        @if(count($posts)>0)
            @foreach ($posts as $post)
                <div class='postItem'>
                    {{-- <h3>{{$post->title}}</h3>
                    <p>Written on {{$post->created}}</p>
                    <p>description {{$post->body}}</p>
                    <p>userId {{$post->userId}}</p>
                    <p>tags {{$post->tags}}</p>
                    <p>upvotes {{$post->upvotes}}</p>
                    <p>downvotes {{$post->downvotes}}</p>
                    <p>views {{$post->views}}</p> --}}
                    <img src='https://scontent.ftxl3-1.fna.fbcdn.net/v/t1.0-9/10418904_1636565699903848_2539602536243785802_n.jpg?_nc_cat=104&_nc_ht=scontent.ftxl3-1.fna&oh=6d173dbe5ea18adcade28cd5ffd43196&oe=5D542243'/>
                </div>    
            @endforeach
        @else
            <p>NO POSTS</p>
        @endif
    </div>
    
@endsection

