<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Rak Saya') }}
            </h2>
            <a href="{{ route('books.create') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                Tambah Buku
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @if (session('status'))
                <div class="p-4 rounded-md bg-green-100 text-green-800 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-md bg-red-100 text-red-800 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @forelse ($books as $book)
                <div class="bg-[var(--surface)] shadow-sm sm:rounded-lg p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <p class="text-lg font-semibold">{{ $book->title }}</p>
                        <p class="text-sm text-gray-600">
                            {{ $book->author }} · {{ $book->pages }} halaman · kondisi {{ $book->condition }}
                        </p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach ($book->genres as $genre)
                                <span class="px-2 py-0.5 rounded-full text-xs text-white"
                                    @style(['background-color: '.$genre->color])>
                                    {{ $genre->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <span class="text-xs uppercase tracking-wide text-gray-500">
                            {{ $book->status->value }}
                        </span>
                        <a href="{{ route('books.edit', $book) }}" class="text-sm underline">Ubah</a>
                        <form method="POST" action="{{ route('books.destroy', $book) }}"
                              onsubmit="return confirm('Hapus buku ini dari rakmu?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 underline">Hapus</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-[var(--surface)] shadow-sm sm:rounded-lg p-10 text-center">
                    <p class="text-lg">Rakmu masih kosong.</p>
                    <p class="mt-1 text-sm text-gray-600">Tambahkan buku pertamamu supaya bisa dipinjam orang lain.</p>
                </div>
            @endforelse

            {{ $books->links() }}
        </div>
    </div>
</x-app-layout>