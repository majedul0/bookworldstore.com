<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use App\Http\Middleware\RolePermissionMiddleware;
use App\Models\User;
use App\Models\UserLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\ImageManagerStatic as Image;

class CustomerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->middleware(RolePermissionMiddleware::class)->only('create', 'store', 'edit', 'update', 'index', 'destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        User::where('admin_read', 2)->update(['admin_read' => 1]);
        // $users = User::where('type', 'customer')->latest('id')->get();

        return view('back.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('back.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $v_data = [
            'name' => 'required|max:255',
            'mobile_number' => 'required|max:191|unique:users',
            'email' => 'nullable|max:191|unique:users',
            'address' => 'required|max:255',
            'password' => 'required|min:8|confirmed',
        ];
        if($request->file('profile')){
            $v_data['profile'] = 'mimes:jpg,png,jpeg,gif';
        }

        $request->validate($v_data);

        $user = new User;
        $user->last_name = $request->name;
        $user->admin_read = 1;
        $user->mobile_number = $request->mobile_number;
        $user->email = $request->email;
        $user->street = $request->address;
        $user->zip = $request->post_code;
        $user->city = $request->city;
        $user->state = $request->province;
        $user->country = $request->country;
        $user->password = Hash::make($request->password);

        if($request->file('profile')){
            $image = $request->file('profile');
            $filename    = time() . '.' . $image->getClientOriginalExtension();

            // Resize Image 150*150
            $image_resize = Image::make($image->getRealPath());
            $image_resize->fit(150, 150);
            $image_resize->save(public_path('/uploads/user/' . $filename));

            $user->profile = $filename;
        }
        $user->save();

        return redirect()->back()->with('success-alert', 'Customer created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $ledgers = UserLedger::where('user_id', $id)->orderBy('id')->get();

        return view('back.customer.show', compact('user', 'ledgers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        // dd($user);
        return view('back.customer.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $v_data = [
            'name' => 'required|max:255',
            'mobile_number' => 'required|max:191|unique:users,mobile_number,' . $id,
            'email' => 'nullable|max:191|unique:users,email,' . $id,
            'address' => 'required|max:255'
        ];
        if($request->file('profile')){
            $v_data['profile'] = 'mimes:jpg,png,jpeg,gif';
        }
        if($request->password){
            $v_data['password'] = 'min:8|confirmed';
        }

        $request->validate($v_data);

        $user = User::findOrFail($id);
        $user->last_name = $request->name;
        $user->mobile_number = $request->mobile_number;
        $user->email = $request->email;
        $user->street = $request->address;
        $user->zip = $request->post_code;
        $user->city = $request->city;
        $user->state = $request->province;
        $user->country = $request->country;
        if($request->password){
            $user->password = Hash::make($request->password);
        }

        if($request->file('profile')){
            $image = $request->file('profile');
            $filename    = time() . '.' . $image->getClientOriginalExtension();

            // Resize Image 150*150
            $image_resize = Image::make($image->getRealPath());
            $image_resize->fit(150, 150);
            $image_resize->save(public_path('/uploads/user/' . $filename));

            // Delete old
            if($user->profile){
                $img = public_path() . '/uploads/user/' . $user->profile;
                if (file_exists($img)) {
                    unlink($img);
                }
            }

            $user->profile = $filename;
        }
        $user->save();

        return redirect()->back()->with('success-alert', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if($user->id == auth()->user()->id){
            return redirect()->back()->with('error-alert', 'Sorry! You can not delete your own account!');
        }

        // Delete Image
        if($user->image){
            $img = public_path() . '/uploads/user/' . $user->image;
            if (file_exists($img)) {
                unlink($img);
            }
        }

        $user->delete();

        return redirect()->back()->with('success-alert', 'Customer deleted successfully.');
    }

    public function selectList(Request $request){
        $search = $request->q;
        $users = User::where(function($q) use ($search){
            $q->where('id', $search)->orWhere('first_name', 'LIKE', "%{$search}%")->orWhere('last_name', 'LIKE', "%{$search}%");
        })->active('id', $request->type, 100)->get();

        // Output
        $output = array();
        foreach ($users as $user){
            $output[] = ['id' => $user->id, 'text' => ($user->full_name . ' - ' . $user->email)];
        }

        return response()->json($output);
    }

    public function action(User $user, $action){
        $user->status = $action;
        $user->save();

        return redirect()->back()->with('success-alert', 'Customer updated successfully.');
    }

    // Table
    public function table(Request $request)
    {
        // Get Data
        $columns = array(
            0 => 'id'
        );

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');

        $query = User::where('type', 'customer');

        // Search
        if ($request->input('search.value')) {
            $search = $request->input('search.value');
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        if($request->with_deleted){
            $query->withTrashed();
        }

        // Count Items
        $totalFiltered = $query->count();
        if ($limit == "-1") {
            $query->skip($start)->limit($totalFiltered);
        } else {
            $query->skip($start)->limit($limit);
        }
        $query = $query->orderBy($order, $dir)->get();

        $output = array();
        foreach ($query as $data) {
            $nestedData['id'] = $data->id;
            $nestedData['name'] = $data->last_name;
            $nestedData['email'] = $data->email;
            $nestedData['mobile_number'] = $data->mobile_number;
            $nestedData['status'] = $data->status_string;
            $nestedData['action'] = '<div class="text-right">
                <a class="btn btn-success btn-sm" href="'. route('back.customers.edit', $data->id) .'"><i class="fas fa-edit"></i></a>

                <form class="d-inline-block" action="'. route('back.customers.destroy', $data->id) .'" method="POST">
                    <input type="hidden" name="_token" value="'. csrf_token() .'">
                    <input type="hidden" name="_method" value="DELETE">

                    <button class="btn btn-danger btn-sm" type="submit" onclick="return confirm(`Are you sure to remove?`)"><i class="fas fa-trash"></i></button>
                </form>

                <!--<a class="btn btn-primary btn-sm" href="'. route('back.customers.show', $data->id) .'">Ledger</a>-->
            </div>';
            $output[] = $nestedData;
        }

        // Output
        $output = array(
            "draw" => intval($request->input('draw')),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $output
        );
        return response()->json($output);
    }
}
