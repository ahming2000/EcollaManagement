<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Models\ItemImage;
use App\Models\ItemUtil;
use App\Models\SystemConfig;
use App\Models\Variation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class ItemsController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'access:status_check']);
    }

    public function index()
    {
        // Get request value
        $paginate = request('paginate') ?? 25;
        $search = request('search') ?? "";
        $category = request('category') ?? "";

        // Generate Where Clause for SQL Query
        $searchClause = $this->generateSearchClause($search, $this->ITEM_SEARCH);
        $category_filterClause = $this->generateFilterClause($category, $this->ITEM_FILTER_CATEGORY);
        $whereClause = $this->combineWhereClause([
            $searchClause,
            $category_filterClause,
        ]);

        // Query all related Item ID
        if ($whereClause != "") {
            $result = DB::table('items')
                ->select('items.id')
                ->join('category_item', 'category_item.item_id', '=', 'items.id')
                ->join('categories', 'categories.id', '=', 'category_item.category_id')
                ->join('variations', 'variations.item_id', '=', 'items.id')
                ->whereRaw($whereClause)
                ->distinct('items.id')
                ->get();
        } else {
            $result = DB::table('items')
                ->select('items.id')
                ->get();
        }

        // Retrieve required data
        $ids = array_column($result->toArray(), 'id');
        $items = Item::whereIn('id', $ids)
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);
        $categories = Category::all();

        // Set pagination links parameter
        $items->withPath('/item' . $this->generateParameter(
                [
                    'paginate' => $paginate,
                    'search' => $search,
                    'category' => $category,
                ])
        );

        // Generate parameter for filtering (search, category, paginate)
        $params = [
            'paginate' => $this->generateParameter(['search' => $search, 'category' => $category], true),
            'category' => $this->generateParameter(['paginate' => $paginate, 'search' => $search], true),
        ];

        return view('item.index', compact('items', 'categories', 'params'));
    }

    public function show(Item $item)
    {
        return view('item.show', compact('item'));
    }

    public function create()
    {
        return view('item.create');
    }

    public function store()
    {
        $data = request()->validate([
            'name' => ['required', 'unique:items']
        ]);

        $item = new Item();
        $item->setAttribute('name', $data['name']);
        $item->save();
        $item->util()->save(new ItemUtil());

        return redirect('/item/' . $item->id . '/edit');
    }

    public function edit(Item $item)
    {
        // Category
        $DEFAULT_CATEGORY_COUNT = SystemConfig::where('name', '=', 'mgmt_i_defaultCategoryCount')->first()->value;

        $categories = Category::whereNotBetween('id', [$DEFAULT_CATEGORY_COUNT + 1, 10])->get();

        // Item
        $item = Item::with("categories")
            ->with("util")
            ->with("discounts")
            ->with("variations")
            ->with("images")
            ->where("items.id", "=", $item->id)
            ->first();

        return view('item.edit2', compact('item', 'categories'));
    }

    public function editOld(Item $item)
    {
        $DEFAULT_CATEGORY_COUNT = SystemConfig::where('name', '=', 'mgmt_i_defaultCategoryCount')->first()->value;
        $categories = Category::whereNotBetween('id', [$DEFAULT_CATEGORY_COUNT + 1, 10])->get();
        return view('item.edit', compact('item', 'categories'));
    }

    public function updateOld(Item $item)
    {
        $data = request()->validate([
            'item.name' => 'required',
            'item.name_en' => '',
            'item.desc' => '',
            'item.origin' => '',
            'item.origin_en' => '',

            'categories.*.id' => '',

            'variations.*.name' => 'required',
            'variations.*.name_en' => '',
            'variations.*.barcode' => 'required',
            'variations.*.price' => '',
            'variations.*.weight' => '',
            'variations.*.stock' => ''
        ]);

        $imageData = request()->validate([
            'item.images.*.newImage' => 'image',
            'item.images.*.oldImage' => '',
            'item.images.*.isEmpty' => '',

            'variations.*.barcode' => '',
            'variations.*.image' => 'image',
            'variations.*.oldImage' => '',
            'variations.*.isEmpty' => ''
        ]);

        // Item
        $temp = DB::table('items')
            ->select('id')
            ->where('name', '=', $data['item']['name'])
            ->where('id', '!=', $item->id)
            ->first();
        if ($temp == null) {
            $item->update($data['item']);
        } else {
            Controller::stackError("此商品名称已被使用！请到<a href=\"/item/" . $temp->id . "/edit\">点击此处</a>添加规格！");
            session()->flash('error', Controller::pullError());
            return redirect('/item/' . $item->id . '/edit');
        }

        // Category
        $this->updateCategoryItem($item, array_column($item->categories->toArray(), 'id'), array_column($data['categories'], 'id'));

        // Variation
        $this->updateVariation($item, $item->variations->toArray(), $data['variations']);

        // General Image
        foreach ($imageData['item']['images'] as $img) {
            if ($img['isEmpty']) {
                // Press delete button and left it (Direct delete)
                if (isset($img['oldImage'])) {
                    $id = DB::table('item_images')
                        ->select('id')
                        ->where('image', '=', $img['oldImage'])
                        ->first()
                        ->id;
                    DB::table('item_images')->delete($id);
                } // else do nothing to ignore the empty image
            } else {
                if (isset($img['newImage'])) {
                    $imagePath = $img['newImage']->store('items/' . $item->id . '');
                    $this->processImage(public_path("img/$imagePath"));

                    $imagePath = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . "/img/" . $imagePath;

                    $itemImage = new ItemImage();
                    $itemImage->setAttribute('image', $imagePath);
                    $item->images()->save($itemImage);

                    // Save new image and delete the old one (replace)
                    if (isset($img['oldImage'])) {
                        $id = DB::table('item_images')
                            ->select('id')
                            ->where('image', '=', $img['oldImage'])
                            ->first()
                            ->id;
                        ItemImage::find($id)->delete();
                    }
                } // else do nothing to remain the image
            }
        }

        // Variation Image
        foreach ($imageData['variations'] as $v) {
            if ($v['isEmpty']) {
                // Press delete button and left it (Direct delete)
                if (isset($v['oldImage'])) {
                    Variation::where('barcode', '=', $v['barcode'])->update(['image' => null]);
                } // else do nothing to ignore the empty image
            } else {
                if (isset($v['image'])) {
                    $imagePath = $v['image']->store('items/' . $item->id . '');
                    $this->processImage(public_path("img/$imagePath"));

                    $imagePath = $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . "/img/" . $imagePath;

                    DB::table('variations')
                        ->where('barcode', '=', $v['barcode'])
                        ->update(['image' => $imagePath]);

                    // Save new image and delete the old one (replace)
                    if (isset($v['oldImage'])) {
                        $id = DB::table('variations')
                            ->where('barcode', '=', $v['barcode'])
                            ->update(['image' => null]);
                    }
                } // else do nothing to remain the image
            }
        }

        // Check the item can be listed or not
        if ($this->canList($item->id, true)) {
            if (session()->has('error')) {
                session()->flash('message', '部分资料已保存！');
            } else {
                session()->flash('message', '商品资料已保存并成功上架！');
            }
        } else {
            session()->flash('message', '商品资料已保存，但未上架！');
        }

        return redirect('/item/' . $item->id . '/edit');
    }

    public function list(Item $item): bool
    {
        if ($item->util->is_listed) {
            $item->util()->update(['is_listed' => false]);
            return true;
        } else {
            if ($this->canList($item->id)) {
                $item->util()->update(['is_listed' => true]);
                return true;
            } else {
                return false;
            }
        }
    }

    public function resetUtil(Item $item, string $attr): bool
    {
        $item->util()->update([$attr => 0]);
        return true;
    }

    public function destroy(Item $item)
    {
        DB::beginTransaction();
        try {
            $item->images()->delete();
            $item->util()->delete();
            $item->variations()->delete();
            $item->categories()->detach();
            $item->discounts()->delete();
            $item->userRating()->delete();
            $item->delete();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            session()->flash('message', '商品删除失败！请联系客服！');
        }

        return redirect('/item')->with('message', "成功删除 " . $item->name . "!");
    }

    private function canList(int $item_id, bool $list = false): bool
    {
        $obj = Item::find($item_id);

        $item = $obj->toArray();
        $variationCount = sizeof($obj->variations->toArray());
        $variations = $obj->variations->toArray();

        if (
            $item['name'] == null ||
            $item['name_en'] == null ||
            $item['desc'] == null ||
            $item['origin'] == null ||
            $item['origin_en'] == null || // Make sure item attribute is filled
            $variationCount < 1 // Make sure have at least one variation
        ) {
            if ($list) $obj->util()->update(['is_listed' => '0']);
            return false;
        } else {
            foreach ($variations as $variation) { // Make sure all variation have filled all attribute except image, stock, weight, price
                if (
                    $variation['barcode'] == null ||
                    $variation['name'] == null ||
                    $variation['name_en'] == null
                ) {
                    if ($list) $obj->util()->update(['is_listed' => '0']);
                    return false;
                }
            }
        }

        if ($list) $obj->util()->update(['is_listed' => '1']);
        return true;
    }

    private function updateVariation(Item $item, array $old, array $new)
    {
        // Replace all null value with default value
        for ($i = 0; $i < sizeof($new); $i++) {
            $new[$i]['price'] = $new[$i]['price'] ?? 0;
            $new[$i]['weight'] = $new[$i]['weight'] ?? 0;
            $new[$i]['stock'] = $new[$i]['stock'] ?? 0;
        }

        $oldBarcode = array_column($old, 'barcode');
        $newBarcode = array_column($new, 'barcode');

        $toAddBarcode = array_diff($newBarcode, $oldBarcode);
        $toDeleteBarcode = array_diff($oldBarcode, $newBarcode);
        $toUpdateBarcode = array_intersect($newBarcode, $oldBarcode);

        $old = $this->generateArrayKeyFromElement($old, 'barcode');
        $new = $this->generateArrayKeyFromElement($new, 'barcode');

        // To add
        foreach ($toAddBarcode as $ta) {
            $variation = new Variation();
            foreach ($new[$ta] as $key => $value) {
                $variation->setAttribute($key, $value);
            }

            if ($this->variationIsDuplicated($item->variations->toArray(), $variation->barcode, $item->id)) {
                Controller::stackError("货号：" . $variation->barcode . " 已存在数据库！");
            } else {
                $item->variations()->save($variation);
            }
        }

        // To delete
        foreach ($toDeleteBarcode as $td) {
            $id = $old[$td]['id'];
            Variation::find($id)->delete();
        }

        // To update
        foreach ($toUpdateBarcode as $tu) {
            $id = $old[$tu]['id'];
            DB::table('variations')
                ->where('id', '=', $id)
                ->update($new[$tu]);
        }

        if ($this->hasError()) {
            $error = Controller::pullError();
            session()->flash('error', $error);
        }
    }

    private function processImage(string $path, $mode = 'frame')
    {
        // $mode can be 'crop'(fit) or 'frame'(resizeCanvas)

        $image = Image::make($path);
        $min = $image->getWidth() < $image->getHeight() ? $image->getWidth() : $image->getHeight();
        $max = $image->getWidth() > $image->getHeight() ? $image->getWidth() : $image->getHeight();

        if ($mode == 'crop') {
            $image->fit($min);
        } else {
            if ($image->width() > $max) {
                $image->resize($max, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            if ($image->height() > $max) {
                $image->resize(null, $max, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            $image->resizeCanvas($max, $max, 'center', false, '#ffffff');
        }
        $image->save();
    }

    private function updateCategoryItem(Item $item, array $old, array $new)
    {

        for ($i = 0; $i < sizeof($old); $i++) {
            $old[$i] = strval($old[$i]);
        }

        $toAdd = array_diff($new, $old);
        $toRemove = array_diff($old, $new);

        // To add
        foreach ($toAdd as $ta) {
            DB::table('category_item')->insert(['item_id' => $item->id, 'category_id' => $ta]);
        }

        // To remove
        foreach ($toRemove as $tr) {
            $item->categories()->detach($tr);
        }
    }

    private function variationIsDuplicated(array $variations, string $barcode, string $item_id): bool
    {
        $result = DB::table('variations')
            ->where('barcode', '=', $barcode)
            ->where('item_id', '!=', $item_id)
            ->get();
        return !empty($result->toArray());
    }
}
