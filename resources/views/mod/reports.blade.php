@extends('layouts.app')

@section('title', 'Home page')

@section('content')
    <script src={{ asset('js/mod.js') }}></script>
    <div class="lg:w-2/5 md:w-3/4 w-full m-auto">
        @if (count($reports) == 0)
            <div class="p-2 bg-white rounded shadow-md mt-2">
                <p class='text-center'>Brak reportów! Wróć do grania w minecrafta.</p>
            </div>
        @endif

        @foreach ($reports as $r)
            <div class="p-2 bg-white rounded shadow-md mt-2">
                <div class='text-center'>
                    <span class='text-sm'>{{ $r->comment->post->created_at->format('d.m.Y')}} - </span>
                    @if (strlen($r->comment->post->title) > 49)
                        <span class='text-center border-b font-bold'>{{ substr($r->comment->post->title, 0, 49) . '...'}}</span>
                    @else
                        <span class='text-center border-b font-bold'>{{ $r->comment->post->title}}</span>
                    @endif
                </div>
                <div class='flex'>
                    <div class='w-1/5 flex flex-col justify-center pr-2'>
                        <div class='text-center'>
                            <span>{{ $r->comment->user->name}}</span>
                        </div>
                        <div class='text-center text-sm text-red-500 font-bold'>
                            <span>{{ $r->amount}} reports</span>
                        </div>
                        <div class='text-center text-xs'>
                            <span>{{ $r->created_at->format('d.m.Y')}}</span>
                        </div>
                    </div>

                    <div class='w-4/5 flex items-center'>
                        <span>{{ $r->comment->comment}}</span>
                    </div>

                    <div class='w-1/6'>
                        <div com-id='{{ $r->comment_id }}' action='1' r-id='{{$r->id}}' class='ActionButton bg-black text-center text-white border-b border-white cursor-pointer hover:bg-white hover:text-black hover:shadow-md'>
                            <i class="material-icons">done</i>
                        </div>
                        <div com-id='{{ $r->comment_id }}' action='2' r-id='{{$r->id}}' class='ActionButton bg-black text-center text-white border-b border-white cursor-pointer hover:bg-white hover:text-black hover:shadow-md'>
                            <i class="material-icons">restore_from_trash</i>
                        </div>
                        <div com-id='{{ $r->comment_id }}' action='3' r-id='{{$r->id}}' class='bg-black text-center text-white border-b border-white cursor-pointer hover:bg-white hover:text-black hover:shadow-md'>
                            <i class="material-icons">info</i>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="p-2 bg-white rounded shadow-md mt-2 flex justify-center">
            <ul class='flex'>
                <li>
                    <a href="{{URL('/mod/reports?p=' . $left)}}" class='block px-4 py-2 bg-orange-400 text-white border cursor-pointer hover:bg-blue-500'>&#8617;</a>
                </li>
                @foreach ($pages as $p)
                    @if ($p == $page)
                        <li>
                            <a href="{{URL('/mod/reports?p=' . $p)}}" class='block px-4 py-2 bg-blue-400 text-white border cursor-pointer hover:bg-blue-500 selected'>{{$p}}</a>
                        </li>
                    @else
                        <li>
                            <a href="{{URL('/mod/reports?p=' . $p)}}" class='block px-4 py-2 bg-blue-400 text-white border cursor-pointer hover:bg-blue-500'>{{$p}}</a>
                        </li>
                    @endif
                @endforeach
                
                <li>
                    <a href="{{URL('/mod/reports?p=' . $right)}}" class='block px-4 py-2 bg-orange-400 text-white border cursor-pointer hover:bg-blue-500'>&#8618;</a>
                </li>
            </ul>
        </div>
    </div>
@endsection