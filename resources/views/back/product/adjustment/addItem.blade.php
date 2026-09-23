<tr>
    <td>{{$product->id}} <input type="hidden" name="product[]" value="{{$product->id ?? ''}}"></td>
    <td>{{$product->title}}</td>
    <td>
        <img src="{{$product->img_paths['small']}}" style="width:35px">
    </td>
    <td>
        @if($product->type == 'Variable')
            <select name="product_data_id[]" class="form-control form-control-sm change_variation" required>
                <option value="">Select Variation</option>
                @foreach ($product->VariableProductData as $product_data)
                    <option value="{{$product_data->id}}">{{$product_data->attribute_items_string}}</option>
                @endforeach
            </select>
        @else
            N/A
            <input type="hidden" name="product_data_id[]" value="{{$product->ProductData->id ?? ''}}">
        @endif
    </td>
    {{-- <td>
        <select name="type[]" class="form-control form-control-sm type_input input_calc" required>
            <option value="Addition">Addition</option>
            <option value="Subtraction">Subtraction</option>
        </select>
    </td> --}}
    {{-- <td>
        <input type="number" name="tax[]" class="form-control form-control-sm tax_input input_calc" style="width: 120px" placeholder="Ex: 5/5%">
    </td>
    <td>
        <input type="number" name="discount[]" class="form-control form-control-sm discount_input input_calc" style="width: 120px" placeholder="Ex: 5/5%">
    </td> --}}
    <td>
        <input type="number" name="price[]" class="form-control form-control-sm price_input input_calc" data-type="per_unit" value="{{$product->ProductData->cost ?? ($product->VariableProductData[0]->cost ?? '')}}" style="width: 120px" step="any">
    </td>
    <td>
        <input type="number" name="quantity[]" class="form-control form-control-sm quantity_input input_calc" value="1" style="width: 120px" required>
    </td>
    <td>
        <input type="number" name="sub_total[]" class="form-control form-control-sm sub_total input_calc" data-type="subtotal" style="width: 120px" value="{{$product->ProductData->cost ?? ($product->VariableProductData[0]->cost ?? '0')}}" step="any">
    </td>
    <td class="text-right">
        <button class="btn btn-danger btn-sm remove_item" type="button"><i class="fas fa-trash"></i></button>
    </td>
</tr>
