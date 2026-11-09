namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Genre;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Book::where('status', 'available')
            ->with(['genres', 'owner.region'])
            ->search($request->q)
            ->inGenre($request->genre_id);

        if ($request->boolean('in_my_city') && $user?->region_id) {
            $query->whereHas('owner', function ($q) use ($user) {
                $q->where('region_id', $user->region_id);
            });
        } else {
            $query->orderByUserRegion($user?->region_id);
        }

        $books = $query->paginate(12)->withQueryString();
        $genres = Genre::all();

        return view('search.index', compact('books', 'genres'));
    }
}