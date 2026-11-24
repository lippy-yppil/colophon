@props(['books'])

<div class="shelf-back">
    <div class="flex items-end gap-1 px-4 pt-8 overflow-x-auto">
        @foreach ($books as $book)
            <x-book-spine :book="$book" />
        @endforeach
    </div>
</div>
<div class="shelf-plank"></div>