<?php

namespace App\Http\Requests;

use App\Enums\BookStatus;
use App\Models\Loan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreLoanRequest extends FormRequest
{
    public const MAX_ACTIVE_LOANS = 3;

    /** Buku hidden milik orang lain tidak boleh diakses (403). */
    public function authorize(): bool
    {
        $book = $this->route('book');

        return ! ($book->status === BookStatus::Hidden
            && $book->owner_id !== $this->user()->id);
    }

    public function rules(): array
    {
        return [
            'borrower_note' => ['nullable', 'string', 'max:500'],
            'due_date' => ['required', 'date', 'after:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'due_date.required' => 'Usulan tenggat wajib diisi.',
            'due_date.after' => 'Tenggat harus setelah hari ini.',
            'borrower_note.max' => 'Catatan maksimal 500 karakter.',
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $v) {
            $book = $this->route('book');
            $user = $this->user();

            if ($book->owner_id === $user->id) {
                $v->errors()->add('book', 'Kamu tidak bisa meminjam buku milik sendiri.');

                return;
            }

            if (Loan::where('book_id', $book->id)->active()->exists()) {
                $v->errors()->add('book', 'Buku ini sedang punya pinjaman aktif dan belum bisa dipinjam.');
            }

            if (Loan::where('borrower_id', $user->id)->active()->count() >= self::MAX_ACTIVE_LOANS) {
                $v->errors()->add(
                    'book',
                    'Kamu sudah punya '.self::MAX_ACTIVE_LOANS.' pinjaman aktif. Kembalikan salah satu dulu.'
                );
            }
        });
    }
}