<div class="variationBox mb-2">
    @php
        $attribute_item_ids = '';
    @endphp
    @foreach ($attributes as $attribute)
        @php
            $attribute_item_ids .= "$attribute->id:,";
        @endphp

        <select class="form-control form-control-sm d-inline-block attribute_selection" data-id="{{$attribute->id}}" style="width: 150px">
            <option value="">Select {{$attribute->name}}</option>
            @foreach ($attribute->AttributeItems as $attribute_item)
                <option value="{{$attribute_item->id}}">{{$attribute_item->name}}</option>
            @endforeach
        </select>
    @endforeach

    <input type="hidden" class="attribute_item_ids" name="attribute_item_ids[]" value="{{$attribute_item_ids}}">

    <button type="button" class="float-right btn btn-danger btn-sm customTitle removeVariationBox" title="Remove">
    <i class="fas fa-times"></i>
    </button>
    <button type="button" class="vbButton float-right btn btn-info btn-sm customTitle mr-2 collapsed" title="Open" data-toggle="collapse" data-target="#vb_{{time()}}" aria-expanded="false">
    <i class="fas fa-fw fa-angle-double-down"></i>
    </button>

    <div id="vb_{{time()}}" class="collapse">
        <hr>

        @include('back.product.extra.info-input', [
            'type' => 'variable'
        ])

       <button type="button" class="btn btn-secondary btn-sm customTitle collapsed" title="Hide" data-toggle="collapse" data-target="#vb_{{time()}}" aria-expanded="false">
       <i class="fas fa-fw fa-angle-double-up"></i>
       </button>
    </div>
</div>
