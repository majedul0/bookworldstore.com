<tr>
    <td>
        {{$product->id}} <input type="hidden" name="products[]" value="{{$product->id ?? ''}}">

        <input type="hidden" name="tax_amount_input_hidden[]" class="tax_amount_input_hidden" value="{{$product->ProductData->calculated_tax_amount ?? 0}}">
    </td>
    <td>
        {{$product->title}}
        {{$product->type == 'Variable' ? '' : ('('. $product->stock .'pcs)')}}
    </td>
    <td>
        <img src="{{$product->img_paths['small']}}" style="width:35px">
    </td>
    <td>
        @if($product->type == 'Variable')
            <div class="form-group">
                <select name="product_data_id[]" class="form-control form-control-sm variation_select" required>
                    <option value="" disabled selected>Select Variation</option>
                    @foreach ($product->VariableProductData as $product_data)
                        <option value="{{$product_data->id}}">{{$product_data->attribute_items_string}}({{$product_data->stock}}pcs)</option>
                    @endforeach
                </select>
            </div>
            <input type="hidden" class="maximum_quantity" value="0">
        @else
            <input type="hidden" name="product_data_id[]" value="{{$product->ProductData->id ?? ''}}">
            <input type="hidden" class="maximum_quantity" value="{{$product->ProductData->stock ?? 0}}">
        @endif

        @foreach ($product->Attributes as $attribute)
            <div class="form-group">
                <label>{{$attribute->name}}</label>
                <select name="{{$product->id}}_simple_attributes[]" class="form-control form-control-sm">
                    <option value="" disabled selected>Select Attribute</option>
                    @foreach ($attribute->AttributeItems as $attribute_item)
                        <option value="{{$attribute_item->id}}">{{$attribute_item->name}}</option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </td>
    <td>
        <input type="number" name="price[]" value="{{$product->type == 'Variable' ? 0 : $product->ProductData->sale_price}}" class="form-control form-control-sm price_input input_calc" style="width: 120px" readonly>
    </td>
    <td>
        <input type="number" name="quantity[]" class="form-control form-control-sm quantity_input" value="1" style="width: 120px" required>
    </td>
    <td>
        <input type="number" name="sub_total[]" value="{{$product->type == 'Variable' ? 0 : $product->ProductData->sale_price}}" class="form-control form-control-sm sub_total" style="width: 120px" value="0" readonly>
    </td>
    <td class="text-right">
        <button class="btn btn-danger btn-sm remove_item" type="button"><i class="fas fa-trash"></i></button>
    </td>
</tr>
