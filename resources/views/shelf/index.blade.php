<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">Rak Buku Komunitas</h2>
        <p class="text-sm" style="color: var(--muted)">Setiap rak milik satu pemilik. Klik punggung buku untuk melihat detailnya.</p>
    </x-slot>

    <div class="max-w-6xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @forelse ($owners as $owner)
            @php
                $isMine = (int) \Illuminate\Support\Facades\Auth::id() === (int) $owner->id;
                $total = $owner->books->count();
            @endphp

            <section class="mb-12">
                <div class="mb-3 flex flex-wrap items-end justify-between gap-2">
                    <div>
                        <h3 class="text-xl font-semibold">
                            <a href="{{ route('shelf.user', $owner) }}" class="hover:underline">Rak {{ $owner->name }}</a>
                            @if ($isMine)
                                <span class="ml-2 align-middle text-xs px-2 py-0.5 rounded-full text-white"
                                      style="background: var(--accent)">Rakmu</span>
                            @endif
                        </h3>
                        <p class="text-sm" style="color: var(--muted)">
                            {{ $owner->region?->name ?? 'Kota belum diatur' }} · {{ $total }} buku
                        </p>
                    </div>

                    @if ($total > 14)
                        <a href="{{ route('shelf.user', $owner) }}" class="text-sm underline">Lihat semua {{ $total }} buku</a>
                    @endif
                </div>

                <x-shelf-row :books="$owner->books->take(14)" />
            </section>
        @empty
            <div class="bg-[var(--surface)] shadow-sm sm:rounded-lg p-10 text-center">
                <p class="text-lg">Belum ada buku di rak mana pun.</p>
            </div>
        @endforelse

        {{ $owners->links() }}
    </div>
</x-app-layout>