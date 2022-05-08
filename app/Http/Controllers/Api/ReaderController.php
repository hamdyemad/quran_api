<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reader;
use App\Traits\File;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ReaderController extends Controller
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
        $readers = Reader::latest()->paginate(10);
        return $this->sendRes('', true, $readers);
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
            'name' => 'required|unique:readers,name',
            'avatar' => "mimes:jpg,jpeg,png,webp|max:$this->kilobyte"
        ], [
            'name.required' => 'الأسم مطلوب',
            'name.unique' => 'الأسم موجود بالفعل',
            'avatar.mimes' => 'صيغة الصورة خطأ',
            'avatar.max' => "يجب اضافة صورة أقل من  ($this->kilobyte كيلو بايت)"

        ]);
        $creation = ['name' => $request->name];
        if($validator->fails()) {
            return $this->sendRes('يوجد خطأ ما', false, $validator->errors());
        }
        if($request->avatar) {
            $creation['avatar'] = $this->uploadFile($request, $this->readersPath, 'avatar');
        }
        Reader::create($creation);
        return $this->sendRes('تم اضافة القارئ بنجاح', true);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reader = Reader::with('pictures')->find($id);
        if($reader) {
            return $reader;
        } else {
            return $this->sendRes('القارئ غير موجود', false);
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
        $reader = Reader::find($id);
        if($reader) {
            $validator = Validator::make($request->all(), [
                'name' => ['required', Rule::unique('readers', 'name')->ignore($reader->id)],
                'avatar' => "mimes:jpg,jpeg,png,webp|max:$this->kilobyte"

            ], [
                'name.required' => 'الأسم مطلوب',
                'name.unique' => 'الأسم موجود بالفعل',
                'avatar.mimes' => 'صيغة الصورة خطأ',
                'avatar.max' => "يجب اضافة صورة أقل من  ($this->kilobyte كيلو بايت)"
            ]);
            $updatedArr = ['name' => $request->name];
            if($validator->fails()) {
                return $this->sendRes('يوجد خطأ ما', false, $validator->errors());
            }
            if($request->avatar) {
                if(file_exists($reader->avatar)) {
                    unlink($reader->avatar);
                }
                $updatedArr['avatar'] = $this->uploadFile($request, $this->readersPath, 'avatar');
            }
            $reader->update($updatedArr);
            return $this->sendRes('تم تعديل القارئ بنجاح', true);
        } else {
            return $this->sendRes('القارئ غير موجود', false);
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
        $reader = Reader::find($id);
        if($reader) {
            if(file_exists($reader->avatar)) {
                unlink($reader->avatar);
            }
            if(count($reader->pictures) > 0) {
                foreach ($reader->pictures as $picture) {
                    if(file_exists($picture->quran)) {
                        unlink($picture->quran);
                    }
                }
            }
            Reader::destroy($reader->id);
            return $this->sendRes('تم ازالة القارئ بنجاح', true);
        } else {
            return $this->sendRes('القارئ غير موجود', false);
        }

    }
}
