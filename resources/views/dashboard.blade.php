<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Dashboard</h2>
    </x-slot>

    <div class="py-8 max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        @include('loans._alerts')

        <div class="grid gap-8 lg:grid-cols-2">

            {{-- Panel 1: Rak Saya --}}
            <section class="space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold">Rak Saya</h3>
                    <a href="{{ route('books.index') }}" class="text-sm underline">Kelola buku ({{ $myBooksCount }})</a>
                </div>

                @if ($myBooksCount === 0)
                    <div class="border rounded p-4">
                        <p>Rak kamu masih kosong.</p>
                        <a href="{{ route('books.create') }}" class="underline">Tambah buku pertama</a>
                    </div>
                @endif

                <h4 class="font-medium">Permintaan masuk</h4>
                @forelse ($incomingLoans as $loan)
                    <article class="border rounded p-4 space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="font-semibold">{{ $loan->book->title }}</p>
                                <p class="text-sm">
                                    Peminjam: {{ $loan->borrower->name }}
                                    @if ($loan->borrower->region) ({{ $loan->borrower->region->name }}) @endif
                                </p>
                                @if ($loan->due_date)
                                    <p class="text-sm">Tenggat: {{ $loan->due_date->translatedFormat('d M Y') }}</p>
                                @endif
                                @if ($loan->borrower_note)
                                    <p class="text-sm italic">"{{ $loan->borrower_note }}"</p>
                                @endif
                            </div>
                            <x-loan-stamp :loan="$loan" />
                        </div>

                        @if ($loan->status->value === 'requested')
                            <form method="POST" action="{{ route('loans.approve', $loan) }}" class="space-y-2">
                                @csrf @method('PATCH')
                                <textarea name="owner_note" rows="2" maxlength="500"
                                          placeholder="Catatan / titik temu (opsional)"
                                          class="w-full border rounded px-2 py-1 text-sm"></textarea>
                                <div class="flex gap-2">
                                    <button class="px-3 py-1 rounded bg-green-700 text-white text-sm">Setujui</button>
                                    <button formaction="{{ route('loans.reject', $loan) }}"
                                            class="px-3 py-1 rounded bg-red-700 text-white text-sm">Tolak</button>
                                </div>
                            </form>
                        @elseif ($loan->status->value === 'approved')
                            <form method="POST" action="{{ route('loans.handover', $loan) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1 rounded bg-blue-800 text-white text-sm">Tandai Diserahkan</button>
                            </form>
                        @elseif ($loan->status->value === 'borrowed')
                            <form method="POST" action="{{ route('loans.return', $loan) }}">
                                @csrf @method('PATCH')
                                <button class="px-3 py-1 rounded bg-green-800 text-white text-sm">Tandai Dikembalikan</button>
                            </form>
                        @endif

                        <a href="{{ route('loans.show', $loan) }}" class="text-sm underline">Lihat kartu pinjam</a>
                    </article>
                @empty
                    <p class="text-sm text-gray-600">Belum ada permintaan masuk.</p>
                @endforelse
            </section>

            {{-- Panel 2: Pinjaman Saya --}}
            <section class="space-y-4">
                <h3 class="text-lg font-semibold">Pinjaman Saya</h3>

                @forelse ($myLoans as $loan)
                    <article class="border rounded p-4 space-y-2">
                        <div class="flex items-start justify-between gap-2">
                            <div>
                                <p class="font-semibold">{{ $loan->book->title }}</p>
                                <p class="text-sm">
                                    Pemilik: {{ $loan->book->owner->name }}
                                    @if ($loan->book->owner->region) ({{ $loan->book->owner->region->name }}) @endif
                                </p>
                                @if ($loan->due_date)
                                    <p class="text-sm">Tenggat: {{ $loan->due_date->translatedFormat('d M Y') }}</p>
                                @endif
                                @if ($loan->owner_note)
                                    <p class="text-sm italic">Pemilik: "{{ $loan->owner_note }}"</p>
                                @endif
                            </div>
                            <x-loan-stamp :loan="$loan" />
                        </div>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('loans.show', $loan) }}" class="text-sm underline">Lihat kartu pinjam</a>
                            @if (in_array($loan->status->value, ['requested', 'approved'], true))
                                <form method="POST" action="{{ route('loans.cancel', $loan) }}"
                                      onsubmit="return confirm('Batalkan permintaan ini?')">
                                    @csrf @method('PATCH')
                                    <button class="text-sm text-red-700 underline">Batalkan</button>
                                </form>
                            @endif
                        </div>
                    </article>
                @empty
                    <div class="border rounded p-4">
                        <p>Kamu belum meminjam buku.</p>
                        <a href="{{ \Illuminate\Support\Facades\Route::has('search.index') ? route('search.index') : url('/search') }}" class="underline">Cari buku</a>
                    </div>
                @endforelse
            </section>
        </div>
    </div>
</x-app-layout>