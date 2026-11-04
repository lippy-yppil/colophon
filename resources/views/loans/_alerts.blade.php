@if (session('status'))
    <div class="rounded border border-green-700 bg-green-50 px-4 py-3 text-green-800">
        {{ session('status') }}
    </div>
@endif

@if ($errors->any())
    <div class="rounded border border-red-700 bg-red-50 px-4 py-3 text-red-800">
        <ul class="list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif