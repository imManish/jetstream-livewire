<?php

namespace App\Http\Livewire;

use App\Models\Project;
use App\Models\ProjectItem;
use App\Models\Item;
use App\Models\Linkeditem;
use Mediconesystems\LivewireDatatables\Column;
use Mediconesystems\LivewireDatatables\DateColumn;
use Mediconesystems\LivewireDatatables\NumberColumn;
use Mediconesystems\LivewireDatatables\Http\Livewire\LivewireDatatable;
use Hash;
use Auth;

class ProjectTable extends LivewireDatatable
{
    public $modalFormVisible = false;
    public $copyProject = false;
    public $modalConfirmDeleteVisible;
    public $modelId;
    public $title;
    public $pickup_date;   
    public $shipping_date;
    public $start_date;
    public $end_date;
    public $exp_return_date;   
    public $ProjectItem = [];
    public $stockProducts = [];
	public $items;
	
	
	/**
	* Add product Item.
	* @return object
	*/
	public function addProduct()
    {
		$this->stockProducts[] = ['id' => '', 'quantity' => 1];
		//dd($this->stockProducts);
    }
	
	public function rules()
    {
        return [      
            'title' =>'required',
            'pickup_date' =>'required|date',
            'shipping_date' => 'required|date|after_or_equal:pickup_date',
            'start_date' => 'required|date|after_or_equal:shipping_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'exp_return_date' => 'required|date|after_or_equal:end_date',
        ];
    }
	
	/**
     * check product linked. 
     * @return object
     */
	public function checkProduct()
    {
		$product = end($this->stockProducts);
        if(@$product){
			$linkeditem = Linkeditem::where('item_id', $product['id'])->get();
			if(@$linkeditem){
				foreach($linkeditem as $linked){
					$this->stockProducts[] = ['id' => $linked->linked_item_id, 'quantity' => 1];
				}
			}
		}
	}
	
	
    /**
     * Remove product item.
     * @return value 
     */
    public function removeProduct($index)
    {
        unset($this->stockProducts[$index]);
        $this->stockProducts = array_values($this->stockProducts);
    }
	
	/**
     * The create function.
     *
     * @return void
     */
    public function create()
    {
		#dd($this->modelData());
        $this->resetErrorBag();
        $this->validate();
        $perform = Project::create($this->modelData());

        foreach($this->stockProducts as $stock){
            $perform->items()->create([
                'quantity' =>  $stock['quantity'],
                'item_id'  => $stock['id'],
                'user_id'  => auth()->user()->id,
                'project_id' => $perform->id
            ])->user()->associate(auth()->user()->id)->save();
        }
        $this->modalFormVisible = false;
        $this->reset();
        redirect()->to('/projects');
    }
	/**
     * Shows the create modal
     *
     * @return void
     */
    public function createCopyModal($id)
    {
        $this->modalFormVisible = true;
		$this->modelId = $id;
        $this->copyProject = true;
        $this->loadModel();
    }
	
	
    public function builder()
    {
		$this->stockProducts = [
            ['id' => '', 'quantity' => 1]
        ];
		
        // $this->stockProducts = array_map(function ($projectItem) {
            // $item = Item::find($projectItem["itemable_id"]);
			// if(@$item){
				// $item->quantity = $projectItem["quantity"];
			// }
            // return $item;
        // }, ProjectItem::latest()->get()->toArray());
		
		
		//return Project::latest()
		$project = Project::query();
		if(request()->search){
			$search = request()->search;
			$date = date('Y-m-d');
			if($search=='in-future'){
				$project = $project->whereDate('pickup_date', '>', $date);
			}
			if($search=='live'){
				$project = $project->whereDate('pickup_date', '>=', $date);
				$project = $project->whereDate('expected_return_date', '<=', $date);
			}
			
			if($search=='archived'){
				$project = $project->whereDate('expected_return_date', '<', $date);
			}
		};
		return $project;
    }

    /**
     * Shows the form modal
     * in update mode.
     *
     * @param  mixed $id
     * @return void
     */
    public function updateShowModal($id)
    {
        $this->modalFormVisible = true;
        $this->modelId = $id;
        $this->copyProject = false;
        $this->loadModel();
    }
	
	/**
		* The update function
     *
     * @return void
     */
	public function update()
    {
        $this->validate();
        Project::find($this->modelId)->update($this->modelData());
		
		$perform = Project::find($this->modelId);
		foreach($this->stockProducts as $stock){
            $perform->items()->create([
                'quantity' =>  $stock['quantity'],
                'itemable_id'  => $stock['id'],
                'user_id'  => auth()->user()->id,
                'project_id' => $perform->id
            ])->user()->associate(auth()->user()->id)->save();
        }
		
        $this->modalFormVisible = false;
    }

    /**
     * Shows the delete confirmation modal.
     *
     * @param  mixed $id
     * @return void
     */
    public function deleteShowModal($id)
    {
        $this->modelId = $id;
        $this->modalConfirmDeleteVisible = true;
    }

	/**
     * The data for the model mapped
     * in this component.
     *
     * @return void
     */
    public function modelData()
    {
        return [
            'title' => $this->title,
            'pickup_date' => $this->pickup_date,
            'shipping_date' => $this->shipping_date,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'expected_return_date' => $this->exp_return_date,
            'status' => 1,
            'user_id' => auth()->user()->id,
            'organisation_id' => auth()->user()->organization_id,
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id
        ];
    }
	
    /**
     * The delete function.
     *
     * @return void
     */
    public function deleteProject()
    {
        Project::destroy($this->modelId);
        $this->modalConfirmDeleteVisible = false;
        $this->resetPage();
    }

    /**
     * Loads the model data
     * of this component.
     *
     * @return void
     */
    public function loadModel()
    {
        $data = Project::find($this->modelId);
        // Assign the variables here
		
		$projectitem = ProjectItem::where('project_id', $data->id)->get();

		if($this->copyProject){
			$this->title = '';
		}
		else {
			$this->title = $data->title;
		}
		
        $this->pickup_date = $data->pickup_date;
        $this->shipping_date = $data->shipping_date;
        $this->start_date = $data->start_date;
        $this->end_date = $data->end_date;
        $this->exp_return_date = $data->expected_return_date;		
		
		$this->ProjectItem = $projectitem ;
		
		if($projectitem){
			foreach($projectitem as $k => $item){
				if($k==0){
					$this->stockProducts = [
						['id' => $item->item_id, 'quantity' => $item->quantity]
					];
				}
			}
		}
    }


    public function columns()
    {
        return [
            Column::name('title')->searchable(),

            DateColumn::name('pickup_date'),
            DateColumn::name('start_date'),
            DateColumn::name('end_date'),
            DateColumn::name('expected_return_date'),
			
            Column::callback(['id'], function ($id) {
                return view('ProjectTable-actions', ['id' => $id, 'modelId' => $id ]);
            })
        ];
    }
}