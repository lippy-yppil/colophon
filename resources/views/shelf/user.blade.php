<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">Rak {{ $user->name }}</h2>
        <p class="text-sm" style="color: var(--muted)">
            {{ $user->region?->name ?? 'Kota belum diatur' }} · {{ $books->count() }} buku
        </p>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @forelse ($books->chunk(12) as $row)
            <div class="mb-10">
                <x-shelf-row :books="$row" />
            </div>
        @empty
            <div class="bg-[var(--surface)] shadow-sm sm:rounded-lg p-10 text-center">
                <p class="text-lg">Rak ini masih kosong.</p>
            </div>
        @endforelse

        <a href="{{ route('shelf.index') }}" class="text-sm underline">&larr; Kembali ke semua rak</a>
    </div>
</x-app-layout>