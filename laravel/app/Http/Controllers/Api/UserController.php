<?php

namespace App\Http\Controllers\Api;

use App\Models\User;

use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Http\Responses\ResponseCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;


class UserController extends Controller
{
    private $GetResponseCode;
    private $RequestFrom;
    private $FormatForm;
    private $DateNow;
    public function __construct(Request $request)
    {
        $this->FormatForm = [
            'name' => [
                'type' => 'string',
                'unique' => true,
                'required' => true
            ],
            'username' => [
                'type' => 'string',
                'unique' => true,
                'required' => true
            ],
            'email' => [
                'type' => 'string',
                'unique' => true,
                'required' => true
            ],
            'password' => [
                'type' => 'string',
                'required' => true
            ],
            'password_confirmation' => [
                'type' => 'string',
                'required' => true
            ]
        ];
        $this->GetResponseCode = new ResponseCode();
        $this->RequestFrom = $request->header('User-Agent');

        setlocale(LC_TIME, 'id_ID.utf8');
        $this->DateNow = date('Y-m-d H:i:s');
    }

    private function isValid($type, $value)
    {
        $pattern = $type === 'username' ? '/^[a-zA-Z0-9_]{4,16}$/' : '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/';
        return preg_match($pattern, $value);
    }

    private function rules()
    {
        return [
            'username' => ['required', 'string', 'min:4', 'max:255', Rule::unique(User::class)],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        ];
    }

    private function createUser($request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json(['status' => $this->GetResponseCode->BAD_REQUEST(), 'message' => 'Data gagal disimpan.', 'error' => $validator->failed()]);
        }

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'status_user' => 1,
            'id_role' => 8,
            'password' => Hash::make($request->password),
            'created_at' =>  $this->DateNow,
            'updated_at' =>  $this->DateNow,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return response()->json(['status' => $this->GetResponseCode->CREATED(), 'message' => 'Data berhasil disimpan.']);
    }

    private function updateUser($request, $dataByID)
    {
        // $validator = Validator::make($request->all(), $this->rules());

        // if ($validator->fails()) {
        //     return response()->json(['status' => $this->GetResponseCode->BAD_REQUEST(), 'message' => 'Data gagal disimpan.', 'error' => $validator->failed()]);
        // }

        $dataByID->update([
            'name' => $request->name,
            'username' => $request->username,
            'status_user' => $request->status_user,
            'umur' => $request->umur,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'updated_at' =>  $this->DateNow,
        ]);

        return response()->json(['status' => $this->GetResponseCode->CREATED(), 'message' => 'Data berhasil disimpan.']);
    }

    public function index()
    {
        $datas = User::all();

        if (count($datas) === 0) {
            return response()->json(['status' => $this->GetResponseCode->OKE(), 'message' => 'Tidak ada data.'], $this->GetResponseCode->OKE());
        }
        return response()->json(['status' => $this->GetResponseCode->OKE(), 'message' => 'Berhasil mengambil data.', 'datas' => $datas], $this->GetResponseCode->OKE());
    }

    public function store(Request $request)
    {
        // if (empty($request->username) || empty($request->email) || empty($request->password)) {
        //     return response()->json(['status' => $this->GetResponseCode->NOT_FOUND(), 'message' => 'Form input tidak sesuai atau value kosong.', 'format' => $this->FormatForm], $this->GetResponseCode->NOT_FOUND());
        // }

        // if (!($this->isValid('username', $request->username)) || !($this->isValid('email', $request->email))) {
        //     return response()->json(['status' => $this->GetResponseCode->NOT_FOUND(), 'message' => 'Format email atau password tidak sesuai.'], $this->GetResponseCode->NOT_FOUND());
        // }

        // $datas = User::all();
        // if (count($datas) === 0) {
        //     $user = $this->createUser($request);

        //     event(new Registered($user));

        //     Auth::login($user);

        //     return response()->json(['status' => $this->GetResponseCode->CREATED(), 'message' => 'Data berhasil disimpan.']);
        // };

        // foreach ($datas as $key => $value) {
        //     if ($value['username'] === $request->username) {
        //         return response()->json(['status' => $this->GetResponseCode->OKE(), 'message' => 'Tidak dapat menggunakan username.']);
        //     }
        //     if ($value['email'] === $request->email) {
        //         return response()->json(['status' => $this->GetResponseCode->OKE(), 'message' => 'Tidak dapat menggunakan email.']);
        //     }
        // }

        return $this->createUser($request);
    }

    public function show(string $id)
    {
        $datas = User::find($id);

        if (empty($datas)) {
            return response()->json(['status' => $this->GetResponseCode->OKE(), 'message' => 'Tidak ada data.'], $this->GetResponseCode->OKE());
        }
        return response()->json(['status' => $this->GetResponseCode->OKE(), 'message' => 'Berhasil mengambil data.', 'datas' => $datas], $this->GetResponseCode->OKE());
    }

    public function update(Request $request, string $id)
    {
        // if (empty($request->username) || empty($request->email) || empty($request->password)) {
        //     return response()->json(['status' => $this->GetResponseCode->NOT_FOUND(), 'message' => 'Form input tidak sesuai atau value kosong.', 'format' => $this->FormatForm], $this->GetResponseCode->NOT_FOUND());
        // }

        // if (!($this->isValid('username', $request->username)) || !($this->isValid('email', $request->email))) {
        //     return response()->json(['status' => $this->GetResponseCode->NOT_FOUND(), 'message' => 'Format email atau password tidak sesuai.'], $this->GetResponseCode->NOT_FOUND());
        // }

        $dataByID = User::find($id);
        // if (empty($dataByID)) {
        //     return response()->json(['status' => $this->GetResponseCode->OKE(), 'message' => 'Tidak ada data.'], $this->GetResponseCode->OKE());
        // }

        // $datas = User::all();
        // foreach ($datas as $key => $value) {
        //     if ($value['username'] === $request->username) {
        //         return response()->json(['status' => $this->GetResponseCode->OKE(), 'message' => 'Tidak dapat menggunakan username.']);
        //     }
        //     if ($value['email'] === $request->email) {
        //         return response()->json(['status' => $this->GetResponseCode->OKE(), 'message' => 'Tidak dapat menggunakan email.']);
        //     }
        // }

        return $this->updateUser($request, $dataByID);
    }

    public function destroy(string $id)
    {
        $dataByID = User::find($id);
        if (empty($dataByID)) {
            return response()->json(['status' => $this->GetResponseCode->OKE(), 'message' => 'Tidak ada data.'], $this->GetResponseCode->OKE());
        }

        $dataByID->delete();
        return response()->json(['status' => $this->GetResponseCode->CREATED(), 'message' => 'Data berhasil dihapus.']);
    }
}
