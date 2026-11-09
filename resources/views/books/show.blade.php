<x-app-layout>
    <div class="max-w-4xl mx-auto my-8 p-8 bg-white border border-stone-300 rounded shadow-md">
        <div class="flex flex-col md:flex-row gap-8">
            <div class="w-full md:w-1/3 flex-shrink-0">
                @if($book->cover_path)
                    <img src="{{ asset('storage/' . $book->cover_path) }}" alt="{{ $book->title }}" class="w-full h-auto rounded shadow">
                @else
                    <div class="w-full h-64 flex items-center justify-center text-white rounded shadow" 
                         style="background-color: {{ $book->genres->first()->color ?? '#6b7280' }}">
                        <span class="font-bold text-center p-4">{{ $book->title }}</span>
                    </div>
                @endif
            </div>

            <div class="w-full md:w-2/3">
                <h1 class="text-3xl font-serif font-bold mb-4">{{ $book->title }}</h1>

                <div class="space-y-2 font-mono text-sm">
                    <div class="flex justify-between border-b border-dotted border-stone-400 pb-1">
                        <span>Penulis</span>
                        <span class="font-semibold">{{ $book->author }}</span>
                    </div>
                    <div class="flex justify-between border-b border-dotted border-stone-400 pb-1">
                        <span>Halaman</span>
                        <span>{{ $book->pages }} hal.</span>
                    </div>
                    <div class="flex justify-between border-b border-dotted border-stone-400 pb-1">
                        <span>Kondisi</span>
                        <span class="capitalize">{{ $book->condition }}</span>
                    </div>
                    <div class="flex justify-between border-b border-dotted border-stone-400 pb-1">
                        <span>Pemilik</span>
                        <a href="{{ route('shelf.user', $book->owner) }}" class="underline text-amber-800">{{ $book->owner->name }}</a>
                    </div>
                    <div class="flex justify-between border-b border-dotted border-stone-400 pb-1">
                        <span>Lokasi</span>
                        <span>{{ $book->owner->region->name ?? '-' }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="font-bold mb-2">Sinopsis:</h3>
                    <p class="text-stone-700 leading-relaxed">{{ $book->synopsis ?? 'Tidak ada sinopsis.' }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>