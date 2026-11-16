@auth
    @if (auth()->id() !== $book->owner_id)
        <form method="POST" action="{{ route('loans.store', $book) }}" class="mt-6 space-y-3">
            @csrf
            <h3 class="font-semibold">Ajukan pinjam</h3>

            @include('loans._alerts')

            <div>
                <label for="due_date" class="block text-sm">Usulan tenggat</label>
                <input type="date" id="due_date" name="due_date" required
                       min="{{ now()->addDay()->toDateString() }}"
                       value="{{ old('due_date') }}"
                       class="border rounded px-2 py-1">
            </div>

            <div>
                <label for="borrower_note" class="block text-sm">Catatan untuk pemilik (titik temu, dll.)</label>
                <textarea id="borrower_note" name="borrower_note" rows="3" maxlength="500"
                          class="w-full border rounded px-2 py-1">{{ old('borrower_note') }}</textarea>
            </div>

            <button type="submit" class="px-4 py-2 rounded text-white" style="background: var(--accent)">
                Pinjam
            </button>
        </form>
    @endif
@else
    <p class="mt-6 text-sm">
        <a href="{{ route('login') }}" class="underline">Masuk</a> untuk meminjam buku ini.
    </p>
@endauth