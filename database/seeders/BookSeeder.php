<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $owners = User::orderBy('id')->get();
        $genreIds = Genre::pluck('id', 'name');

        // [judul, penulis, halaman, [genre], sinopsis]
        $books = [
            ['Bumi Manusia', 'Pramoedya Ananta Toer', 535, ['Fiksi', 'Sejarah'], 'Kisah Minke, pribumi terpelajar, di tengah ketimpangan masa kolonial Hindia Belanda.'],
            ['Laskar Pelangi', 'Andrea Hirata', 529, ['Fiksi'], 'Perjuangan sekelompok anak di Belitung untuk tetap bersekolah dengan segala keterbatasan.'],
            ['Cantik Itu Luka', 'Eka Kurniawan', 520, ['Fiksi', 'Sejarah'], 'Saga keluarga Dewi Ayu yang bertaut dengan sejarah dan mitos Nusantara.'],
            ['Laut Bercerita', 'Leila S. Chudori', 379, ['Fiksi', 'Sejarah'], 'Kisah aktivis yang hilang di akhir era Orde Baru, dan keluarga yang menunggu.'],
            ['Pulang', 'Leila S. Chudori', 464, ['Fiksi', 'Sejarah'], 'Pergulatan eksil politik Indonesia di Paris dan kerinduan pada tanah air.'],
            ['Filosofi Teras', 'Henry Manampiring', 346, ['Pengembangan Diri'], 'Pengantar filosofi Stoa untuk menghadapi emosi dan masalah sehari-hari.'],
            ['Atomic Habits', 'James Clear', 320, ['Pengembangan Diri'], 'Cara membangun kebiasaan baik lewat perubahan kecil yang konsisten.'],
            ['Sapiens', 'Yuval Noah Harari', 498, ['Sejarah', 'Sains'], 'Sejarah singkat umat manusia, dari zaman batu sampai era modern.'],
            ['Cosmos', 'Carl Sagan', 365, ['Sains'], 'Perjalanan memahami alam semesta dan tempat manusia di dalamnya.'],
            ['A Brief History of Time', 'Stephen Hawking', 256, ['Sains'], 'Pengantar populer tentang ruang, waktu, dan lubang hitam.'],
            ["Harry Potter and the Philosopher's Stone", 'J.K. Rowling', 332, ['Fantasi'], 'Anak yatim mengetahui dirinya penyihir dan masuk sekolah sihir Hogwarts.'],
            ['The Hobbit', 'J.R.R. Tolkien', 310, ['Fantasi'], 'Bilbo Baggins ikut petualangan merebut harta karun dari seekor naga.'],
            ['Dune', 'Frank Herbert', 688, ['Fiksi Ilmiah'], 'Perebutan planet gurun Arrakis, satu-satunya sumber rempah paling berharga.'],
            ['Neuromancer', 'William Gibson', 271, ['Fiksi Ilmiah'], 'Seorang peretas dunia maya dipekerjakan untuk satu tugas yang berbahaya.'],
            ['Murder on the Orient Express', 'Agatha Christie', 256, ['Misteri'], 'Detektif Poirot menyelidiki pembunuhan di kereta yang terjebak salju.'],
            ['And Then There Were None', 'Agatha Christie', 272, ['Misteri'], 'Sepuluh orang terjebak di sebuah pulau dan satu per satu tewas.'],
            ['Dilan 1990', 'Pidi Baiq', 332, ['Romantis'], 'Kisah cinta remaja di Bandung pada awal tahun 1990-an.'],
            ['Pride and Prejudice', 'Jane Austen', 432, ['Romantis', 'Fiksi'], 'Elizabeth Bennet dan Mr. Darcy belajar melampaui prasangka masing-masing.'],
            ['Habis Gelap Terbitlah Terang', 'R.A. Kartini', 224, ['Sejarah', 'Biografi'], 'Kumpulan surat R.A. Kartini tentang pendidikan dan kesetaraan perempuan.'],
            ['Steve Jobs', 'Walter Isaacson', 656, ['Biografi'], 'Biografi pendiri Apple berdasarkan wawancara langsung dengan dirinya.'],
            ['Educated', 'Tara Westover', 352, ['Biografi'], 'Memoar perempuan yang tumbuh tanpa sekolah lalu meraih gelar doktor.'],
            ['Maus', 'Art Spiegelman', 296, ['Komik', 'Sejarah'], 'Novel grafis tentang kisah ayah penulis sebagai penyintas Holocaust.'],
            ['Persepolis', 'Marjane Satrapi', 153, ['Komik', 'Biografi'], 'Memoar bergambar tentang masa kecil di Iran saat revolusi.'],
            ['Sang Pemimpi', 'Andrea Hirata', 292, ['Fiksi'], 'Kelanjutan kisah Ikal dan Arai mengejar mimpi sekolah ke luar negeri.'],
            ['Perahu Kertas', 'Dee Lestari', 444, ['Romantis', 'Fiksi'], 'Kisah Kugy dan Keenan yang mengejar mimpi dan saling jatuh cinta.'],
            ['1984', 'George Orwell', 328, ['Fiksi', 'Fiksi Ilmiah'], 'Potret masyarakat yang diawasi total oleh negara dan dikontrol bahasanya.'],
            ['The Martian', 'Andy Weir', 369, ['Fiksi Ilmiah'], 'Astronot yang tertinggal sendirian di Mars berjuang bertahan hidup.'],
            ['Gone Girl', 'Gillian Flynn', 432, ['Misteri'], 'Hilangnya seorang istri membuat suaminya menjadi tersangka utama.'],
        ];

        foreach ($books as $i => [$title, $author, $pages, $genreNames, $synopsis]) {
            $book = Book::factory()->create([
                'owner_id' => $owners[$i % $owners->count()]->id,
                'title'    => $title,
                'author'   => $author,
                'pages'    => $pages,
                'synopsis' => $synopsis,
            ]);

            $book->genres()->attach($genreIds->only($genreNames)->values()->all());
        }
    }
}