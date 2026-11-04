<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Kartu Peminjaman #{{ $loan->id }}</h2>
    </x-slot>

    <div class="py-8 max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">
        @include('loans._alerts')

        <div class="border-2 rounded p-6 space-y-4" style="background: var(--surface); color: var(--ink)">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <h3 class="text-xl font-bold">{{ $loan->book->title }}</h3>
                    <p class="text-sm">{{ $loan->book->author }}</p>
                </div>
                <x-loan-stamp :loan="$loan" />
            </div>

            <dl class="grid grid-cols-[9rem_1fr] gap-y-1 text-sm">
                <dt>Peminjam</dt>
                <dd>{{ $loan->borrower->name }}@if ($loan->borrower->region), {{ $loan->borrower->region->name }}@endif</dd>

                <dt>Pemilik</dt>
                <dd>{{ $loan->book->owner->name }}@if ($loan->book->owner->region), {{ $loan->book->owner->region->name }}@endif</dd>

                <dt>Diajukan</dt>
                <dd>{{ $loan->requested_at?->translatedFormat('d M Y') ?? '-' }}</dd>

                <dt>Disetujui</dt>
                <dd>{{ $loan->approved_at?->translatedFormat('d M Y') ?? '-' }}</dd>

                <dt>Tenggat</dt>
                <dd>{{ $loan->due_date?->translatedFormat('d M Y') ?? '-' }}</dd>

                <dt>Dikembalikan</dt>
                <dd>{{ $loan->returned_at?->translatedFormat('d M Y') ?? '-' }}</dd>
            </dl>

            <div class="border-t pt-3 text-sm space-y-2">
                <p><strong>Catatan peminjam:</strong> {{ $loan->borrower_note ?: '-' }}</p>
                <p><strong>Catatan pemilik:</strong> {{ $loan->owner_note ?: '-' }}</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-2">
            @can('cancel', $loan)
                @if (in_array($loan->status->value, ['requested', 'approved'], true))
                    <form method="POST" action="{{ route('loans.cancel', $loan) }}">
                        @csrf @method('PATCH')
                        <button class="px-3 py-1 rounded bg-gray-700 text-white text-sm">Batalkan</button>
                    </form>
                @endif
            @endcan

            @can('approve', $loan)
                @if ($loan->status->value === 'requested')
                    <form method="POST" action="{{ route('loans.approve', $loan) }}" class="flex flex-col gap-2 w-full">
                        @csrf @method('PATCH')
                        <textarea name="owner_note" rows="2" maxlength="500" placeholder="Catatan / titik temu"
                                  class="border rounded px-2 py-1 text-sm"></textarea>
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
            @endcan
        </div>

        <a href="{{ route('dashboard') }}" class="text-sm underline">Kembali ke dashboard</a>
    </div>
</x-app-layout>