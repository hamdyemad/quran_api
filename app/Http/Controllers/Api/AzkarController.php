<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Azkar;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AzkarController extends Controller
{
    use Res;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $azkars = Azkar::latest()->paginate(10);
        return $this->sendRes('', true, $azkars);
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
            'azkar_category_id' => 'required|exists:azkar_categories,id',
            'elzekr' => 'required',
            'about' => 'required',
        ], [
            'azkar_category_id.required' => 'صنف الأذكار مطلوب',
            'azkar_category_id.exists' => 'صنف الأذكار غير موجود',
            'elzekr.required' => 'عنوان الذكر مطلوب',
            'about.required' => 'الذكر مطلوب',
        ]);
        $creation = [
            'azkar_category_id' => $request->azkar_category_id,
            'elzekr' => $request->elzekr,
            'about' => $request->about,
        ];
        if($validator->fails()) {
            return $this->sendRes('يوجد خطأ ما', false, $validator->errors());
        }
        Azkar::create($creation);
        return $this->sendRes('تم اضافة الذكر بنجاح', true);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $elzekr = Azkar::with('category')->find($id);
        if($elzekr) {
            return $elzekr;
        } else {
            return $this->sendRes('الصنف هذا غير موجود', false);
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
        $elzekr = Azkar::find($id);
        if($elzekr) {
            $validator = Validator::make($request->all(), [
                'azkar_category_id' => 'required|exists:azkar_categories,id',
                'elzekr' => 'required',
                'about' => 'required',
            ], [
                'azkar_category_id.required' => 'صنف الأذكار مطلوب',
                'azkar_category_id.exists' => 'صنف الأذكار غير موجود',
                'elzekr.required' => 'عنوان الذكر مطلوب',
                'about.required' => 'الذكر مطلوب',
            ]);
            $creation = [
                'azkar_category_id' => $request->azkar_category_id,
                'elzekr' => $request->elzekr,
                'about' => $request->about,
            ];
            if($validator->fails()) {
                return $this->sendRes('يوجد خطأ ما', false, $validator->errors());
            }
            $elzekr->update($creation);
            return $this->sendRes('تم تعديل الذكر بنجاح', true);
        } else {
            return $this->sendRes('الذكر هذا غير موجود', false);
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
        $elzekr = Azkar::find($id);
        if($elzekr) {
            Azkar::destroy($elzekr->id);
            return $this->sendRes('تم ازالة الذكر بنجاح', true);
        } else {
            return $this->sendRes('الذكر غير موجود', false);
        }

    }
}
