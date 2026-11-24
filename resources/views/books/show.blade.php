<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">Detail Buku</h2>
    </x-slot>

    @php
        $isOwner = (int) $book->owner_id === (int) auth()->id();
        $isAvailable = $book->status === \App\Enums\BookStatus::Available;
        $row = 'flex justify-between gap-4 border-b border-dotted border-stone-400 pb-1';
    @endphp

    <div class="max-w-4xl mx-auto my-8 p-6 sm:p-8 bg-[var(--surface)] border border-stone-300 rounded shadow-md">
        <div class="flex flex-col md:flex-row gap-8">
            <div class="w-full md:w-1/3 flex-shrink-0">
                @if ($book->cover_path)
                    <img src="{{ asset('storage/'.$book->cover_path) }}" alt="Cover {{ $book->title }}"
                         class="w-full h-auto rounded shadow">
                @else
                    <div class="w-full h-64 flex items-center justify-center text-white rounded shadow"
                         @style(['background-color: '.($book->genres->first()?->color ?? '#6b7280')])>
                        <span class="font-bold text-center p-4">{{ $book->title }}</span>
                    </div>
                @endif
            </div>

            <div class="w-full md:w-2/3">
                <h1 class="text-3xl font-bold mb-2 break-words">{{ $book->title }}</h1>

                <div class="mb-4 flex flex-wrap gap-2">
                    @foreach ($book->genres as $genre)
                        <span class="px-2 py-0.5 rounded-full text-xs text-white"
                              @style(['background-color: '.$genre->color])>{{ $genre->name }}</span>
                    @endforeach
                </div>

                <div class="space-y-2 font-mono text-sm">
                    <div class="{{ $row }}"><span>Penulis</span><span class="font-semibold text-right">{{ $book->author }}</span></div>
                    <div class="{{ $row }}"><span>Halaman</span><span>{{ $book->pages }} hal.</span></div>
                    <div class="{{ $row }}"><span>Kondisi</span><span class="capitalize">{{ $book->condition }}</span></div>
                    <div class="{{ $row }}">
                        <span>Pemilik</span>
                        <a href="{{ route('shelf.user', $book->owner) }}" class="underline">{{ $book->owner->name }}</a>
                    </div>
                    <div class="{{ $row }}"><span>Lokasi</span><span>{{ $book->owner->region?->name ?? '-' }}</span></div>
                    <div class="{{ $row }}"><span>Status</span><span class="uppercase">{{ $book->status->value }}</span></div>
                </div>

                <div class="mt-6">
                    <h3 class="font-bold mb-2">Sinopsis</h3>
                    <p class="leading-relaxed">{{ $book->synopsis ?: 'Tidak ada sinopsis.' }}</p>
                </div>

                <div class="mt-8">
                    @if ($isOwner)
                        <a href="{{ route('books.edit', $book) }}" class="underline text-sm">Ubah buku ini</a>
                    @elseif ($isAvailable)
                        @include('loans._request-form', ['book' => $book])
                    @else
                        <p class="text-sm text-gray-600">Buku ini sedang tidak bisa dipinjam.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>