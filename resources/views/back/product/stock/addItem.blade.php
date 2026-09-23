<tr>
    <td>{{$product->id}} <input type="hidden" name="product_id[]" value="{{$product->id ?? ''}}"></td>
    <td>{{$product->title}}</td>
    <td>
        <img src="{{$product->img_paths['small']}}" style="width:35px">
    </td>
    <td>{{$product->type}}</td>
    <td>
        @if($product->type == 'Variable')
            <select name="product_data_id[]" class="form-control form-control-sm" required>
                @foreach ($product->VariableProductData as $product_data)
                    <option value="{{$product_data->id}}">{{$product_data->attribute_items_string}}</option>
                @endforeach
            </select>
        @else
            N/A
            <input type="hidden" name="product_data_id[]" value="{{$product->ProductData->id ?? ''}}">
        @endif
    </td>
    <td>
        <input type="number" name="quantity[]" class="form-control form-control-sm" value="1" style="width: 120px" required>
    </td>
    <td>
        <input type="number" name="price[]" class="form-control form-control-sm" style="width: 120px" required>
    </td>
    <td class="text-right">
        <button class="btn btn-danger btn-sm remove_item" type="button"><i class="fas fa-trash"></i></button>
    </td>
</tr>
