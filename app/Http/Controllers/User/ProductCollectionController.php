<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\UserProduct;
use App\Services\ConflictCheckerService;
use App\Services\IngredientDetectorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductCollectionController extends Controller
{
    protected IngredientDetectorService $detectorService;
    protected ConflictCheckerService $conflictChecker;

    public function __construct(
        IngredientDetectorService $detectorService,
        ConflictCheckerService $conflictChecker
    ) {
        $this->detectorService = $detectorService;
        $this->conflictChecker = $conflictChecker;
    }

    public function index()
    {
        $user = Auth::user();

        $userProducts = $user->userProducts()
            ->with(['product.ingredients', 'ingredients'])
            ->where('is_active', true)
            ->get();

        $masterProducts = Product::orderBy('brand')->orderBy('name')->get();

        $conflictReport = $this->conflictChecker->analyzeUserProducts($userProducts);

        $totalIngredients = $userProducts->flatMap(function ($up) {
            if ($up->product) {
                return $up->product->ingredients;
            }
            return $up->ingredients;
        })->unique('id')->count();

        $activeCount = $userProducts->count();
        $riskyCount = $conflictReport['risky_count'] ?? 0;

        return view('user.products.collection', compact(
            'userProducts',
            'masterProducts',
            'conflictReport',
            'totalIngredients',
            'activeCount',
            'riskyCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'custom_brand' => 'nullable|string|max:100',
            'custom_name' => 'required|string|max:200',
            'custom_category' => 'required|in:cleanser,hydrating_toner,exfoliating_toner,serum,spot_treatment,eye_cream,moisturizer,face_oil,sunscreen,other',
            'usage_time' => 'required|in:morning,night,both',
            'product_id' => 'nullable|exists:products,id',
            'custom_ingredients_raw' => 'nullable|string|max:5000',
        ]);

        $user = Auth::user();

        $userProduct = UserProduct::create([
            'user_id' => $user->id,
            'product_id' => $request->product_id,
            'custom_brand' => $request->custom_brand,
            'custom_name' => $request->custom_name,
            'custom_category' => $request->custom_category,
            'usage_time' => $request->usage_time,
            'custom_ingredients_raw' => $request->custom_ingredients_raw,
            'frequency_per_week' => 7,
            'is_active' => true,
        ]);

        if (!empty($request->custom_ingredients_raw)) {
            $detection = $this->detectorService->detectFromText($request->custom_ingredients_raw);
            if ($detection['detected_ingredients']->isNotEmpty()) {
                $userProduct->ingredients()->sync($detection['detected_ingredients']->pluck('id'));
            }
        }

        return redirect()->route('user.products')->with('success', 'Produk berhasil ditambahkan ke koleksi Anda.');
    }

    public function destroy(UserProduct $userProduct)
    {
        if ($userProduct->user_id !== Auth::id()) {
            abort(403);
        }

        $userProduct->delete();

        return redirect()->route('user.products')->with('success', 'Produk berhasil dihapus dari koleksi.');
    }

    public function toggle(UserProduct $userProduct)
    {
        if ($userProduct->user_id !== Auth::id()) {
            abort(403);
        }

        $userProduct->update([
            'is_active' => !$userProduct->is_active,
        ]);

        return redirect()->route('user.products')->with('success', $userProduct->is_active ? 'Produk diaktifkan kembali.' : 'Produk dinonaktifkan sementara.');
    }
}
