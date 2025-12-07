@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-9">
        <h1 class=" text-2xl">Books</h1>
        <form method="GET" action="{{ route('books.index') }}" class="flex items-center ">

            <div class="relative mr-1">
                <input type="text" name="title" placeholder="nhập tên sách..." value="{{ request('title') }}"
                    class="rounded-md border-green-200" />

                <input type="hidden" name="filter" value="{{ request('filter') }}">

                @if (request('title'))
                    <a href="{{ route('books.index') }}"
                        class="absolute right-0 top-1/2 transform -translate-y-1/2 p-2 text-slate-500 hover:text-slate-800 transition duration-150"
                        title="Xóa tìm kiếm">
                        {{-- Icon Xóa (dùng SVG) --}}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </a>
                @endif

            </div>

            <button type="submit" class="btn">Search</button>
        </form>
    </div>

    <div class="filter-container mb-4 flex">
        @php
            $filters = [
                '' => 'Latest',
                'popular_last_month' => 'Popular last month',
                'popular_last_6months' => 'Popular last 6 months',
                'highest_rated_last_month' => 'Highest rated last month',
                'highest_rated_last_6months' => 'Highest rated last 6months',
            ];
        @endphp

        @foreach ($filters as $key => $label)
            <a href="{{ route('books.index', [...request()->query(), 'filter' => $key]) }}"
                class="{{ request('filter') === $key || (request('filter') === null && $key === '') ? 'filter-item-active' : 'filter-item' }}">
                {{ $label }}
            </a>
        @endforeach

    </div>

    <ul>
        @forelse($books as $book)
            {{-- @dd($book) --}}
            <li class="mb-4">
                <div class="book-item">
                    <div class="flex flex-wrap items-center justify-between">
                        <div class="w-full grow sm:w-auto">
                            <a href="{{ route('books.show', $book) }}" class="book-title">{{ $book->title }}</a>
                            <span class="book-author">by {{ $book->author }}</span>
                        </div>
                        <div>
                            <div class="book-rating">
                                {{ number_format($book->reviews_avg_rating, 1) }}
                            </div>
                            <div class="book-review-count">
                                out of {{ $book->reviews_count }} {{ Str::Plural('review', $book->reviews_count) }}
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li class="mb-4">
                <div class="empty-book-item">
                    <p class="empty-text">No books found</p>
                    <a href="{{ route('books.index') }}" class="reset-link">Reset criteria</a>
                </div>
            </li>
        @endforelse
    </ul>
@endsection
