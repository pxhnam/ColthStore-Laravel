<?php

namespace App\Livewire\Admin;

use App\Models\ProductVariant;
use Exception;
use App\Models\Size;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\Livewire\Forms\ProductForm;
use Illuminate\Support\Facades\Log;


class UpdateProduct extends Component
{
    use WithFileUploads;

    public $title = '';
    public ProductForm $product;
    public $count = 0;


    #[On('create-product')]
    public function create()
    {
        $this->title = 'New Product';
        $this->dispatch('open-modal');
    }

    #[On('update-product')]
    public function update($id)
    {
        $this->product->id = $id;
        if ($id) {
            try {
                $product = Product::findOrFail($id);
                $this->product->name = $product->name;
                $this->product->slug = $product->slug;
                $this->product->category = $product->category_id;
                $this->product->desc = $product->desc;
                $this->product->state = $product->state;
                $this->count = $product->variants->count();
                foreach ($product->variants as $index => $detail) {
                    $this->product->details[$index]['id'] = $detail->id;
                    $this->product->details[$index]['size'] = $detail->size_id;
                    $this->product->details[$index]['color'] = $detail->color_id;
                    $this->product->details[$index]['amount'] = $detail->num;
                    $this->product->details[$index]['cost'] = $detail->cost;
                }
                $this->product->preview = $product->images;
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
                $this->dispatch('error', 'An unexpected error occurred. Please try again.');
                return;
            }
        }
        $this->title = 'Update Size';
        $this->dispatch('open-modal');
    }

    public function genSlug()
    {
        $this->product->slug = Str::slug($this->product->name);
    }

    public function add()
    {
        $this->count++;
    }

    public function removeIndex($index)
    {
        unset($this->product->details[$index]);
        $this->product->details = array_values($this->product->details);

        $this->resetValidation("product.details.$index.size");
        $this->resetValidation("product.details.$index.color");
        $this->resetValidation("product.details.$index.amount");
        $this->resetValidation("product.details.$index.cost");
        $this->count--;
    }
    public function destroyVariant($index, $id)
    {
        try {
            $variant = ProductVariant::findOrFail($id);
            $variant->delete();
            $this->removeIndex($index);
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function removeTemporaryImage($index)
    {
        if (isset($this->product->pics[$index])) {
            unset($this->product->pics[$index]);
            $this->product->pics = array_values($this->product->pics);
        }
    }

    public function destroyImage($id)
    {
        try {
            $image = Image::findOrFail($id);
            $image->delete();
            $this->product->preview = Image::select('id', 'path')
                ->where('product_id', $this->product->id)->get();
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function submit()
    {
        $this->product->validate();

        $combinations = [];
        foreach ($this->product->details as $detail) {
            $combination = $detail['size'] . '-' . $detail['color'];

            if (in_array($combination, $combinations)) {
                $this->dispatch('error', 'The combination of size and color is duplicated');
                return;
            }
            $combinations[] = $combination;
        }
        DB::beginTransaction();
        try {
            $paths = [];
            if ($this->product->pics && is_array($this->product->pics)) {
                foreach ($this->product->pics as $pic) {
                    if ($pic instanceof UploadedFile) {
                        $paths[] = $pic->store('uploads', 'public');
                    }
                }
            }

            if ($this->product->id) {
                $product = Product::findOrFail($this->product->id);
                $product->name = $this->product->name;
                $product->slug = $this->product->slug;
                $product->category_id = $this->product->category;
                $product->state = $this->product->state;
                $product->desc = $this->product->desc;
                $product->save();
                foreach ($paths as $path) {
                    Image::create([
                        'product_id' => $product->id,
                        'path' => $path
                    ]);
                }
                foreach ($this->product->details as $detail) {
                    if (isset($detail['id'])) {
                        ProductVariant::where('id', $detail['id'])->update([
                            'color_id' => $detail['color'],
                            'size_id' => $detail['size'],
                            'num' => $detail['amount'],
                            'cost' => $detail['cost']
                        ]);
                    } else {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'color_id' => $detail['color'],
                            'size_id' => $detail['size'],
                            'num' => $detail['amount'],
                            'cost' => $detail['cost']
                        ]);
                    }
                }
                $this->dispatch('reload');
                $this->dispatch('success', 'Updated Successfully!');
            } else {
                $product = Product::create([
                    'name' => $this->product->name,
                    'slug' => $this->product->slug,
                    'category_id' => $this->product->category,
                    'desc' => $this->product->desc,
                    'state' => $this->product->state
                ]);
                foreach ($paths as $path) {
                    Image::create([
                        'product_id' => $product->id,
                        'path' => $path
                    ]);
                }
                foreach ($this->product->details as $detail) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'color_id' => $detail['color'],
                        'size_id' => $detail['size'],
                        'num' => $detail['amount'],
                        'cost' => $detail['cost']
                    ]);
                }
                $this->count = 0;
                $this->product->reset();
                $this->dispatch('success', 'Created Successfully!');
            }
            $this->dispatch('load-products');
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            Log::error($ex->getMessage());
            $this->dispatch('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function close()
    {
        $this->count = 0;
        $this->product->resetValidation();
        $this->product->reset();
    }

    #[On('reload')]
    public function reloadForm()
    {
        try {
            if ($this->product->id) {
                $product = Product::findOrFail($this->product->id);
                $this->count = $product->variants->count();
                foreach ($product->variants as $index => $detail) {
                    $this->product->details[$index]['id'] = $detail->id;
                    $this->product->details[$index]['size'] = $detail->size_id;
                    $this->product->details[$index]['color'] = $detail->color_id;
                    $this->product->details[$index]['amount'] = $detail->num;
                    $this->product->details[$index]['cost'] = $detail->cost;
                }
                $this->product->preview = $product->images;
                $this->product->pics = [];
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }
    }

    public function render()
    {
        $sizes = Size::select('id', 'name')->get();
        $colors = Color::select('id', 'name')->get();
        $categories = Category::select('id', 'name')->get();
        return view(
            'livewire.admin.update-product',
            [
                'sizes' => $sizes,
                'colors' => $colors,
                'categories' => $categories
            ]
        );
    }
}
