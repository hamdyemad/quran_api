<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Doaa;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DoaaController extends Controller
{
    use Res;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $doaas = Doaa::latest()->paginate(10);
        return $this->sendRes('', true, $doaas);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'doaa' => 'required',
            'about' => 'required',
        ], [
            'doaa.required' => 'عنوان الدعاء مطلوب',
            'about.required' => 'الدعاء مطلوب',
        ]);
        $creation = [
            'doaa' => $request->doaa,
            'about' => $request->about,
        ];
        if($validator->fails()) {
            return $this->sendRes('يوجد خطأ ما', false, $validator->errors());
        }
        Doaa::create($creation);
        return $this->sendRes('تم اضافة الدعاء بنجاح', true);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $doaa = Doaa::find($id);
        if($doaa) {
            return $doaa;
        } else {
            return $this->sendRes('الدعاء هذا غير موجود', false);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $doaa = Doaa::find($id);
        if($doaa) {
            $validator = Validator::make($request->all(), [
                'doaa' => 'required',
                'about' => 'required',
            ], [
                'doaa.required' => 'عنوان الدعاء مطلوب',
                'about.required' => 'الدعاء مطلوب',
            ]);
            $creation = [
                'doaa' => $request->doaa,
                'about' => $request->about,
            ];
            if($validator->fails()) {
                return $this->sendRes('يوجد خطأ ما', false, $validator->errors());
            }
            $doaa->update($creation);
            return $this->sendRes('تم تعديل الدعاء بنجاح', true);
        } else {
            return $this->sendRes('الدعاء هذا غير موجود', false);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $doaa = Doaa::find($id);
        if($doaa) {
            Doaa::destroy($doaa->id);
            return $this->sendRes('تم ازالة الدعاء بنجاح', true);
        } else {
            return $this->sendRes('الدعاء غير موجود', false);
        }

    }
}
