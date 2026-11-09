namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;

class ShelfController extends Controller
{
    // T20: Rak semua pengguna
    public function index()
    {
        $books = Book::where('status', 'available')
            ->with(['genres', 'owner.region'])
            ->latest()
            ->paginate(20);

        return view('shelf.index', compact('books'));
    }

    // T21: Rak milik satu pengguna
    public function userShelf(User $user)
    {
        $books = $user->books()
            ->where('status', '!=', 'hidden')
            ->with('genres')
            ->get();

        return view('shelf.user', compact('user', 'books'));
    }
}