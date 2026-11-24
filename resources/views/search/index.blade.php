<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">Cari Buku</h2>
    </x-slot>

    <div class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8 space-y-6">
        @php $field = 'mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm'; @endphp

        <form method="GET" action="{{ route('search.index') }}"
              class="bg-[var(--surface)] shadow-sm sm:rounded-lg p-5 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="sm:col-span-2 lg:col-span-4">
                <x-input-label for="q" value="Judul atau penulis" />
                <x-text-input id="q" name="q" type="text" class="mt-1 block w-full"
                              :value="request('q')" placeholder="Contoh: pramoedya" />
                <x-input-error class="mt-2" :messages="$errors->get('q')" />
            </div>

            <div>
                <x-input-label for="genre_id" value="Genre" />
                <select id="genre_id" name="genre_id" class="{{ $field }}">
                    <option value="">Semua genre</option>
                    @foreach ($genres as $genre)
                        <option value="{{ $genre->id }}" @selected((string) request('genre_id') === (string) $genre->id)>
                            {{ $genre->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <x-input-label for="region_id" value="Kota" />
                <select id="region_id" name="region_id" class="{{ $field }}">
                    <option value="">Semua kota</option>
                    @foreach ($regions as $region)
                        <option value="{{ $region->id }}" @selected((string) request('region_id') === (string) $region->id)>
                            {{ $region->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <label class="inline-flex items-center gap-2 text-sm self-end pb-2">
                <input type="checkbox" name="in_my_city" value="1"
                       class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                       @checked(request()->boolean('in_my_city'))>
                Di kotaku saja
            </label>

            <div class="self-end flex items-center gap-4">
                <x-primary-button>Cari</x-primary-button>
                <a href="{{ route('search.index') }}" class="text-sm underline text-gray-600">Atur ulang</a>
            </div>
        </form>

        <p class="text-sm text-gray-600">{{ $books->total() }} buku ditemukan</p>

        @forelse ($books as $book)
            <a href="{{ route('books.show', $book) }}"
               class="block bg-[var(--surface)] shadow-sm sm:rounded-lg p-5 hover:shadow-md transition-shadow">
                <p class="text-lg font-semibold">{{ $book->title }}</p>
                <p class="text-sm text-gray-600">
                    {{ $book->author }} · {{ $book->pages }} halaman ·
                    {{ $book->owner->name }} di {{ $book->owner->region?->name ?? 'kota tidak diketahui' }}
                </p>
                <div class="mt-2 flex flex-wrap gap-2">
                    @foreach ($book->genres as $genre)
                        <span class="px-2 py-0.5 rounded-full text-xs text-white"
                              @style(['background-color: '.$genre->color])>{{ $genre->name }}</span>
                    @endforeach
                </div>
            </a>
        @empty
            <div class="bg-[var(--surface)] shadow-sm sm:rounded-lg p-10 text-center">
                <p class="text-lg">Tidak ada buku yang cocok.</p>
                <p class="mt-1 text-sm text-gray-600">Coba kata kunci atau filter lain.</p>
            </div>
        @endforelse

        {{ $books->links() }}
    </div>
</x-app-layout>