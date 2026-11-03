@php
    $isEdit = $book->exists;
    $locked = $isEdit && $book->status === \App\Enums\BookStatus::Borrowed;
    $selectedGenres = old('genres', $isEdit ? $book->genres->pluck('id')->all() : []);
    $currentStatus = old('status', $book->status?->value ?? 'available');
    $currentCondition = old('condition', $book->condition ?? 'baik');
    $field = 'mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm';
@endphp

<form method="POST"
      action="{{ $isEdit ? route('books.update', $book) : route('books.store') }}"
      enctype="multipart/form-data"
      class="space-y-6">
    @csrf
    @if ($isEdit)
        @method('PUT')
    @endif

    <div>
        <x-input-label for="title" value="Judul" />
        <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                      :value="old('title', $book->title)" required autofocus />
        <x-input-error class="mt-2" :messages="$errors->get('title')" />
    </div>

    <div>
        <x-input-label for="author" value="Penulis" />
        <x-text-input id="author" name="author" type="text" class="mt-1 block w-full"
                      :value="old('author', $book->author)" required />
        <x-input-error class="mt-2" :messages="$errors->get('author')" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="pages" value="Jumlah halaman" />
            <x-text-input id="pages" name="pages" type="number" min="1" class="mt-1 block w-full"
                          :value="old('pages', $book->pages)" required />
            <x-input-error class="mt-2" :messages="$errors->get('pages')" />
        </div>
        <div>
            <x-input-label for="isbn" value="ISBN (opsional)" />
            <x-text-input id="isbn" name="isbn" type="text" class="mt-1 block w-full"
                          :value="old('isbn', $book->isbn)" />
            <x-input-error class="mt-2" :messages="$errors->get('isbn')" />
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div>
            <x-input-label for="condition" value="Kondisi buku" />
            <select id="condition" name="condition" class="{{ $field }}" required>
                @foreach (['baik' => 'Baik', 'cukup' => 'Cukup', 'usang' => 'Usang'] as $value => $label)
                    <option value="{{ $value }}" @selected($currentCondition === $value)>{{ $label }}</option>
                @endforeach
            </select>
            <x-input-error class="mt-2" :messages="$errors->get('condition')" />
        </div>
        <div>
            <x-input-label for="status" value="Tampilan di rak" />
            <select id="status" name="status" class="{{ $field }}" @disabled($locked)>
                <option value="available" @selected($currentStatus === 'available')>Tampil di rak publik</option>
                <option value="hidden" @selected($currentStatus === 'hidden')>Disembunyikan</option>
            </select>
            @if ($locked)
                <p class="mt-1 text-sm text-gray-600">Buku sedang dipinjam, jadi statusnya tidak bisa diubah.</p>
            @endif
            <x-input-error class="mt-2" :messages="$errors->get('status')" />
        </div>
    </div>

    <fieldset>
        <legend class="block font-medium text-sm text-gray-700">Genre (pilih 1 sampai 3)</legend>
        <div class="mt-2 flex flex-wrap gap-3">
            @foreach ($genres as $genre)
                <label class="inline-flex items-center gap-2 text-sm">
                    <input type="checkbox" name="genres[]" value="{{ $genre->id }}"
                           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                           @checked(in_array($genre->id, $selectedGenres))>
                    <span class="px-2 py-0.5 rounded-full text-xs text-white"
                        @style(['background-color: '.$genre->color])>{{ $genre->name }}</span>
                </label>
            @endforeach
        </div>
        <x-input-error class="mt-2" :messages="$errors->get('genres')" />
        <x-input-error class="mt-2" :messages="$errors->get('genres.*')" />
    </fieldset>

    <div>
        <x-input-label for="synopsis" value="Sinopsis (opsional)" />
        <textarea id="synopsis" name="synopsis" rows="5" class="{{ $field }}">{{ old('synopsis', $book->synopsis) }}</textarea>
        <x-input-error class="mt-2" :messages="$errors->get('synopsis')" />
    </div>

    <div>
        <x-input-label for="cover" value="Cover (opsional, maksimal 2 MB, jpg, png, atau webp)" />
        <input id="cover" name="cover" type="file" accept="image/jpeg,image/png,image/webp"
               class="mt-1 block w-full text-sm text-gray-700">
        @if ($isEdit && $book->cover_path)
            <p class="mt-1 text-sm text-gray-600">Cover sudah ada. Pilih file baru hanya jika ingin menggantinya.</p>
        @endif
        <x-input-error class="mt-2" :messages="$errors->get('cover')" />
    </div>

    <div class="flex items-center gap-4">
        <x-primary-button>{{ $isEdit ? 'Simpan Perubahan' : 'Simpan Buku' }}</x-primary-button>
        <a href="{{ route('books.index') }}" class="text-sm text-gray-600 underline">Batal</a>
    </div>
</form>