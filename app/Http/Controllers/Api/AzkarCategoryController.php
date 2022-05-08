<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AzkarCategory;
use App\Traits\File;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AzkarCategoryController extends Controller
{


    use Res, File;
    protected $kilobyte = 50 * 1000;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = AzkarCategory::latest()->paginate(10);
        return $this->sendRes('', true, $categories);
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
            'name' => 'required|unique:azkar_categories,name',
            'photo' => "mimes:jpg,jpeg,png,webp|max:$this->kilobyte"
        ], [
            'name.required' => 'الأسم مطلوب',
            'name.unique' => 'الأسم موجود بالفعل',
            'photo.mimes' => 'صيغة الصورة خطأ',
            'photo.max' => "يجب اضافة صورة أقل من  ($this->kilobyte كيلو بايت)"
        ]);
        $creation = ['name' => $request->name];
        if($validator->fails()) {
            return $this->sendRes('يوجد خطأ ما', false, $validator->errors());
        }
        if($request->photo) {
            $creation['photo'] = $this->uploadFile($request, $this->azkarCategories, 'photo');
        }
        AzkarCategory::create($creation);
        return $this->sendRes('تم اضافة صنف الأذكار بنجاح', true);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $azkarCategory = AzkarCategory::with('azkars')->find($id);
        if($azkarCategory) {
            return $azkarCategory;
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
        $azkarCategory = AzkarCategory::find($id);
        if($azkarCategory) {
            $validator = Validator::make($request->all(), [
                'name' => ['required', Rule::unique('azkar_categories', 'name')->ignore($azkarCategory->id)],
                'photo' => "mimes:jpg,jpeg,png,webp|max:$this->kilobyte"
            ], [
                'name.required' => 'الأسم مطلوب',
                'name.unique' => 'الأسم موجود بالفعل',
                'photo.mimes' => 'صيغة الصورة خطأ',
                'photo.max' => "يجب اضافة صورة أقل من  ($this->kilobyte كيلو بايت)"
            ]);
            $updatedArr = ['name' => $request->name];
            if($validator->fails()) {
                return $this->sendRes('يوجد خطأ ما', false, $validator->errors());
            }
            if($request->photo) {
                if(file_exists($azkarCategory->photo)) {
                    unlink($azkarCategory->photo);
                }
                $updatedArr['photo'] = $this->uploadFile($request, $this->azkarCategories, 'photo');
            }
            $azkarCategory->update($updatedArr);
            return $this->sendRes('تم تعديل صنف الأذكار بنجاح', true);
        } else {
            return $this->sendRes('الصنف غير موجود', false);
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
        $azkarCategory = AzkarCategory::find($id);
        if($azkarCategory) {
            if(file_exists($azkarCategory->photo)) {
                unlink($azkarCategory->photo);
            }
            AzkarCategory::destroy($azkarCategory->id);
            return $this->sendRes('تم ازالة الصنف بنجاح', true);
        } else {
            return $this->sendRes('الصنف غير موجود', false);
        }

    }
}
