@extends('back.layouts.master')
@section('title', 'Payment Methods')

@section('master')
<div class="card border-light shadow">
    <div class="card-header">
        <h5 class="d-inline-block">Payment methods</h5>

        <a href="{{route('back.paymentMethods.create')}}" class="btn btn-primary btn-sm float-right"><i class="fas fa-plus"></i> Create new</a>
    </div>
    <div class="card-body table-responsive">
        <table class="table table-bordered table-sm">
            <thead>
              <tr>
                <th scope="col">Sl.</th>
                <th scope="col">Name</th>
                <th scope="col">Number</th>
                <th scope="col">Instructions</th>
                <th scope="col">Status</th>
                <th scope="col" class="text-right">Action</th>
              </tr>
            </thead>
            <tbody>
                @forelse ($payment_methods as $key => $payment_method)
                    <tr>
                        <th scope="row">{{$key + 1}}</th>
                        <td>{{$payment_method->name}}</td>
                        <td>{{$payment_method->number}}</td>
                        <td>{{ \Illuminate\Support\Str::limit($payment_method->instructions, 60) }}</td>
                        <td>
                            @if($payment_method->status)
                                <span class="badge badge-success">Active</span>
                            @else
                                <span class="badge badge-secondary">Inactive</span>
                            @endif
                        </td>
                        <td class="text-right">
                            <a href="{{route('back.paymentMethods.edit', $payment_method->id)}}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>

                            <form class="d-inline-block" action="{{route('back.paymentMethods.destroy', $payment_method->id)}}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure to remove?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No payment methods yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
