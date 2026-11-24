@props(['book'])

@php
    $pages  = max(80, min((int) $book->pages, 800));
    $width  = 30 + (int) round(($pages - 80) / 720 * 26);   // 30 sampai 56 px, mengikuti jumlah halaman
    $height = 210 + (($book->id * 37) % 40);                // 210 sampai 249 px, tetap untuk tiap buku
    $color  = $book->genres->first()?->color ?? '#6b7280';
@endphp

<a href="{{ route('books.show', $book) }}"
   class="book-spine group relative shrink-0 flex items-center justify-center overflow-hidden rounded-t-sm transition-transform duration-200 ease-out hover:-translate-y-3 focus:-translate-y-3 focus:outline-none"
   @style(['--spine: '.$color, 'width: '.$width.'px', 'height: '.$height.'px'])
   title="{{ $book->title }} - {{ $book->author }}">
    <span class="text-white font-semibold text-xs tracking-wide overflow-hidden text-ellipsis whitespace-nowrap"
          @style(['writing-mode: vertical-rl', 'transform: rotate(180deg)', 'max-height: '.($height - 44).'px'])>
        {{ $book->title }}
    </span>
</a>