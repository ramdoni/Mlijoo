<div class="editable">
    @if($is_edit)
        @if($field=='product_uom_id')
            <select class="form-control" wire:model="value">
                <option value=""> --- UOM --- </option>
                @foreach(\App\Models\ProductUom::get() as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach 
            </select>
        @else
            <input type="text" class="form-control" style="height:30px;" wire:model="value" wire:keydown.enter="save" placeholder="{{$field}}"  />
        @endif
        <a href="javascript:void(0)" wire:click="$set('is_edit',false)"><i class="fa fa-close text-danger"></i></a>
        <a href="javascript:void(0)" wire:click="save"><i class="fa fa-save text-success"></i></a>
    @else

        @if($field=='price' || $field=='disc')
            <a href="javascript:void(0)" wire:click="$set('is_edit',true)">{!!$value?format_idr($value):'<i style="color:grey">edit</i>'!!}</a>
        @else
            <a href="javascript:void(0)" wire:click="$set('is_edit',true)">{!!$value?$value:'<i style="color:grey">edit</i>'!!}</a>
        @endif
    @endif
</div>
