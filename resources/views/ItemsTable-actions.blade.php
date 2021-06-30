<div class="flex space-x-1 justify-around">
    
    <a wire:click="updateShowModal({{ $id }})" target="_blank" class="p-1 text-coolGray-900 hover:text-coolGray-900 hover:text-gray-400 rounded">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"></path></svg>
	</a>
	
    <button wire:click="deleteShowModal({{ $id }})" class="p-1 text-coolGray-900 hover:text-coolGray-900 hover:text-gray-400 rounded">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
	</button>
</div>


{{-- Modal Form --}}
<x-jet-dialog-modal wire:model="modalFormVisible">
	<x-slot name="title">
		{{ __('Create or Update Item') }}
		{{ $this->modelId }}
		
	</x-slot>
	
	<x-slot name="content">
		<div class="mt-4">
			<x-jet-label for="" value="{{ __('Item name') }}" />
			<x-jet-input wire:model="name" id="" class="block mt-1 w-full" type="text" />
			@error('name') <span class="error">{{ $message }}</span> @enderror
		</div>  
		<div class="mt-4">
			<x-jet-label for="" value="{{ __('Type') }}" />
			
			<select wire:model="item_type_id" id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
				<option value="">-- Select a Item type --</option>
				@foreach (App\Models\ItemType::getItemTypes() as $key => $value)
				<option value="{{ $value['id'] }}">{{ $value['name'] }}</option>    
				@endforeach
			</select>
			
			@error('item_type_id') <span class="error">{{ $message }}</span> @enderror
		</div>		
	
		@if($this->item_type_id == 1)
		<div class="mt-4">
			<x-jet-label for="" value="{{ __('Cable length') }}" />
			<x-jet-input wire:model="cable_length" id="" class="block mt-1 w-full" type="text" />
			@error('cable_length') <span class="error">{{ $message }}</span> @enderror
		</div> 
		@else
		<div class="mt-4">
			<x-jet-label for="" value="{{ __('Make') }}" />
			<x-jet-input wire:model="make" id="" class="block mt-1 w-full" type="text" />
			@error('make') <span class="error">{{ $message }}</span> @enderror
		</div> 
		<div class="mt-4">
			<x-jet-label for="" value="{{ __('Model') }}" />
			<x-jet-input wire:model="model" id="" class="block mt-1 w-full" type="text" />
			@error('model') <span class="error">{{ $message }}</span> @enderror
		</div> 
		@endif
		
		<div class="mt-4" wire:ignore>
			<x-jet-label for="" value="{{ __('Linked Items') }}" />
			
			<select wire:model="linked_items" multiple id="" class="block appearance-none w-full bg-gray-100 border border-gray-200 text-gray-700 py-3 px-4 pr-8 round leading-tight focus:outline-none focus:bg-white focus:border-gray-500 form-select-engineerSearch">
				<option value="">-- Select a Linked Items --</option>    
				@foreach (App\Models\Item::getlinkeditems() as $key => $value)
				@if($this->modelId!=$value['id'])
				<option value="{{ $value['id'] }}" selected="{{ in_array($value['id'], $this->linked_items) ?? '' }}">{{ $value['name'] }}</option>
				@endif
				@endforeach
			</select>
			
			@error('linked_items') <span class="error">{{ $message }}</span> @enderror
		</div>
		
	</x-slot>
	
	<x-slot name="footer">
		<x-jet-secondary-button wire:click="$toggle('modalFormVisible')" wire:loading.attr="disabled">
			{{ __('Cancel') }}
		</x-jet-secondary-button>
		
		@if ($modelId)
		<x-jet-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
			{{ __('Update') }}
		</x-jet-danger-button>
		@else
		<x-jet-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
			{{ __('Create') }}
		</x-jet-danger-button>
		@endif            
	</x-slot>
</x-jet-dialog-modal>

{{-- The Delete Modal --}}
<x-jet-dialog-modal wire:model="modalConfirmDeleteVisible">
	<x-slot name="title">
		{{ __('Delete Item') }}
	</x-slot>
	
	<x-slot name="content">
		{{ __('Are you sure you want to delete this item?') }}
	</x-slot>
	
	<x-slot name="footer">
		<x-jet-secondary-button wire:click="$toggle('modalConfirmDeleteVisible')" wire:loading.attr="disabled">
			{{ __('Cancel') }}
		</x-jet-secondary-button>
		
		<x-jet-danger-button class="ml-2" wire:click="deleteItem" wire:loading.attr="disabled">
			{{ __('Confirm delete') }}
		</x-jet-danger-button>
	</x-slot>
</x-jet-dialog-modal>


<script>
	

	window.onload = function() {
		$('.form-select-engineerSearch').select2({width: '100%', sorter: data => data.sort((a, b) => a.text.localeCompare(b.text)),});
		$('.form-select-engineerSearch').on('change', function (e) {
			var data = $('.form-select-engineerSearch').select2("val");
			@this.set('linked_items', data);
		});
	}
</script>