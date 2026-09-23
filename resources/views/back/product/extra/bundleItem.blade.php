<tr>
    <td>
        <input type="hidden" name="products[]" value="{{$product->id}}">
        <img src="{{$product->img_paths['small']}}" style="height: 80px;width: auto;">
    </td>
    <td>{{$product->title}}</td>
    <td>{{amount($product->sale_price)}}</td>
    <td>
        <button class="btn btn-danger btn-sm removeBundle" onclick="return confirm('Are you sure to remove?');"><i class="fas fa-trash"></i></button>
    </td>
</tr>
