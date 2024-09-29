<?php

namespace App\Livewire\Admin;

use Exception;
use Livewire\Component;
use App\Models\Category;
use Illuminate\Support\Str;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Livewire\Forms\CategoryForm;

class UpdateCategory extends Component
{
    use WithFileUploads;

    public $title = '';
    public CategoryForm $category;

    #[On('create-category')]
    public function create()
    {
        $this->title = 'New Category';
        $this->dispatch('open-modal');
    }

    #[On('update-category')]
    public function update($id)
    {
        if ($id) {
            try {
                $this->category->id = $id;
                $category = Category::findOrFail($id);
                $this->category->name = $category->name;
                $this->category->slug = $category->slug;
                $this->category->url = $category->pic;
                $this->category->desc = $category->desc;
            } catch (Exception $ex) {
                Log::error($ex->getMessage());
                $this->dispatch('error', 'An unexpected error occurred. Please try again.');
                return;
            }
        }
        $this->title = 'Update Color';
        $this->dispatch('open-modal');
    }

    public function genSlug()
    {
        $this->category->slug = Str::slug($this->category->name);
    }

    public function submit()
    {
        // $this->category->children = $this->getChildCategories($this->category->id);
        $this->category->validate();
        try {
            if ($this->category->pic instanceof UploadedFile) {
                $this->category->url = $this->category->pic->store('uploads', 'public');
            }
            if ($this->category->id) {
                $category = Category::findOrFail($this->category->id);
                $category->name = $this->category->name;
                $category->slug = $this->category->slug;
                $category->desc = $this->category->desc;
                if ($this->category->pic) {
                    $category->pic = $this->category->url;
                }
                $category->save();
                $this->category->reset('pic');
                $this->dispatch('success', 'Updated Successfully!');
            } else {
                $category = Category::create([
                    'name' => $this->category->name,
                    'slug' => $this->category->slug,
                    'pic' => $this->category->url,
                    'desc' => $this->category->desc
                ]);
                $this->category->reset();
                $this->dispatch('success', 'Created Successfully!');
            }
            $this->dispatch('load-categories');
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
            $this->dispatch('error', 'An unexpected error occurred. Please try again.');
        }
    }

    public function close()
    {
        $this->category->resetValidation();
        $this->category->reset();
    }

    private function getChildCategories($parentId)
    {
        $children = Category::where('parent', $parentId)->pluck('id')->toArray();

        foreach ($children as $child) {
            $children = array_merge($children, $this->getChildCategories($child));
        }

        return $children;
    }

    public function render()
    {
        $parents = Category::select('id', 'name')
            ->get();

        return view(
            'livewire.admin.update-category',
            [
                'parents' => $parents
            ]
        );
    }
}
