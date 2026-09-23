<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Regular Price</label>
            <input type="number" class="form-control form-control-sm" name="{{$name_prefix ?? ''}}regular_price{{$type == 'variable' ? '[]' : ''}}" step="any" value="{{(old('regular_price') && $type != 'variable') ? '' : ($data->regular_price ?? '')}}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Sale Price*</label>
            <input type="number" class="form-control form-control-sm sale_price" name="{{$name_prefix ?? ''}}sale_price{{$type == 'variable' ? '[]' : ''}}" step="any" value="{{(old('sale_price') && $type != 'variable') ? '' : ($data->sale_price ?? '')}}" {{$type == 'simple' ? 'required' : ''}}>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Rack Number</label>
            <input type="number" class="form-control form-control-sm" name="{{$name_prefix ?? ''}}rack_number{{$type == 'variable' ? '[]' : ''}}" value="{{(old('rack_number') && $type != 'variable') ? '' : ($data->rack_number ?? '')}}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Product Unit*</label>
            <select name="{{$name_prefix ?? ''}}unit{{$type == 'variable' ? '[]' : ''}}" class="form-control form-control-sm unit" {{$type == 'simple' ? 'required' : ''}}>
                <option value="Pcs" {{($data->unit ?? '') == 'Pcs' ? 'selected' : ''}}>Pcs</option>
                <option value="G" {{($data->unit ?? '') == 'G' ? 'selected' : ''}}>G</option>
                <option value="KG" {{($data->unit ?? '') == 'KG' ? 'selected' : ''}}>KG</option>
                <option value="ML" {{($data->unit ?? '') == 'ML' ? 'selected' : ''}}>ML</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Unit Amount*</label>
            <input type="number" class="form-control form-control-sm unit_amount" step="any" name="{{$name_prefix ?? ''}}unit_amount{{$type == 'variable' ? '[]' : ''}}" value="{{(old('unit_amount') && $type != 'variable') ? '' : ($data->unit_amount ?? 1)}}" {{$type == 'simple' ? 'required' : ''}}>
        </div>
    </div>

    @if($type == 'variable')
        <div class="col-md-4">
            @isset ($data)
                <div class="text-left">
                    <img src="{{$data->img_paths['small']}}" style="width: 70px">
                </div>
            @endisset

            <div class="form-group">
                <label>Image</label>
                <div class="custom-file text-left">
                    @isset($name_prefix)
                    <input type="file" class="custom-file-input" name="{{$name_prefix ? ($name_prefix . $data->id) : ''}}variation_image">
                    @else
                    <input type="file" class="custom-file-input" name="variation_image[]">
                    @endisset

                    <label class="custom-file-label">Choose file...</label>
                </div>
            </div>
        </div>
    @endif

    @if(($data ?? null) && ($type == 'simple'))
        <div class="col-md-4">
            <div class="form-group">
                <label>Manage Stock By*</label>
                <select name="manage_stock_by" class="form-control form-control-sm manage_stock_by">
                    <option value="Self" {{$data->stock_managed_by == 'Self' ? 'selected' : ''}}>Self</option>
                    <option value="Other" {{$data->stock_managed_by == 'Other' ? 'selected' : ''}}>Other</option>
                </select>
            </div>
        </div>
    @endif
</div>

@if($data ?? null)
<div class="other_stock" style="display: {{$data->stock_managed_by == 'Other' ? 'block' : 'none'}}">
    <div class="row">
        <div class="col-md-8">
            <div class="form-group">
                <label>Source Product*</label>
                <br>
                <select class="form-control selectpicker_products" name="source_product"></select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>Conversion Fact*</label>
                <input type="number" step="any" name="stock_conversion_fact" value="{{old('stock_conversion_fact', $data->stock_conversion_fact)}}" class="form-control form-control-sm">
            </div>
        </div>
    </div>
</div>
@endif
