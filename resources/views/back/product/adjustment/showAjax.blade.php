<div class="modal-body detailsModalBody table-responsive">
    <table class="table table-sm table-bordered">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">Image</th>
                <th scope="col">Variation</th>
                <th scope="col" class="text-center" style="width: 120px">Unit Cost</th>
                <th scope="col" class="text-center" style="width: 120px">Quantity</th>
                <th scope="col" class="text-right" style="width: 120px">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($adjustment->Stocks as $stock)
                <tr>
                    <td scope="col">{{$stock->Product->id ?? ''}}</td>
                    <td scope="col">{{$stock->Product->title ?? ''}}</td>
                    <td scope="col"><img src="{{$stock->Product->img_paths['small'] ?? ''}}" style="width:35px"></td>
                    <td scope="col">{{$stock->ProductData->attribute_items_string ?? ''}}</td>
                    <td scope="col" class="text-center" style="width: 120px">{{$settings_g['currency_symbol'] . number_format($stock->purchase_price ?? 0, 2)}}</td>
                    <td scope="col" class="text-center" style="width: 120px">{{$stock->purchase_quantity}}</td>
                    <td scope="col" class="text-right" style="width: 120px">{{$settings_g['currency_symbol'] . number_format(($stock->current_quantity ?? 1) * ($stock->purchase_price ?? 0), 2)}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
