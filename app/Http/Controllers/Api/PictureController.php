<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Picture;
use App\Traits\File;
use App\Traits\Res;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class PictureController extends Controller
{
    protected $kilobyte = 50 * 1000;
    use Res, File;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pictures = Picture::paginate(10);
        return $this->sendRes('', true, $pictures);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'reader_id' => 'required|exists:readers,id',
            'name' => 'required|unique:pictures,name',
            'quran' => "required|file|max:$this->kilobyte|mimes:mp3"
        ], [
            'reader_id.required' => 'أسم القارئ مطلوب',
            'reader_id.exists' => 'أسم القارئ يجب أن يكون موجود',
            'name.required' => 'أسم الصورة مطلوبة',
            'name.unique' =>  'أسم الصورة موجودة بالفعل',
            'quran.required' =>  'الصورة مطلوبة',
            'quran.mimes' =>  'الصيغة يجب أن تكون أوديو من نوع mp3',
            'quran.file' => 'نوع الملف mp3',
            'quran.max' => "يجب اضافة صورة أقل من  ($this->kilobyte كيلو بايت)"
        ]);
        if($validator->fails()) {
            return $this->sendRes('يوجد خطأ ما', false, $validator->errors());
        }
        Picture::create([
            'reader_id' => $request->reader_id,
            'name' => $request->name,
            'quran' => $this->uploadFile($request, $this->picturesPath, 'quran')
        ]);
        return $this->sendRes('تم اضافة الصورة بنجاح', true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $picture = Picture::with('reader')->find($id);
        if($picture) {
            return $picture;
        } else {
            return $this->sendRes('الصورة غير موجودة', false);
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
        $picture = Picture::find($id);
        if($picture) {
            $validator = Validator::make($request->all(), [
                'reader_id' => 'exists:readers,id',
                'name' => ['required', Rule::unique('pictures', 'name')->ignore($id)],
                'quran' => "file|max:$this->kilobyte|mimes:mp3"
            ], [
                'reader_id.exists' => 'أسم القارئ يجب أن يكون موجود',
                'name.required' => 'أسم الصورة مطلوبة',
                'name.unique' =>  'أسم الصورة موجودة بالفعل',
                'quran.mimes' =>  'الصيغة يجب أن تكون أوديو من نوع mp3',
                'quran.file' => 'نوع الملف mp3',
                'quran.max' => "يجب اضافة صورة أقل من  ($this->kilobyte كيلو بايت)"
            ]);
            $updatedArr = [
                'reader_id' => $request->reader_id,
                'name' => $request->name,
            ];
            if($request->reader_id) {
                $updatedArr['reader_id'] = $request->reader_id;
            } else {
                unset($updatedArr['reader_id']);
            }
            if($validator->fails()) {
                return $this->sendRes('يوجد خطأ ما', false, $validator->errors());
            }
            if($request->quran) {
                if(file_exists($picture->quran)) {
                    unlink($picture->quran);
                }
                $updatedArr['quran'] = $this->uploadFile($request, $this->picturesPath, 'quran');
            }
            $picture->update($updatedArr);
            return $this->sendRes('تم تعديل الصورة بنجاح', true);

        } else {
            return $this->sendRes('الصورة غير موجودة', false);

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
        $picture = Picture::find($id);
        if($picture) {
            if(file_exists($picture->quran)) {
                unlink($picture->quran);
            }
            Picture::destroy($picture->id);
            return $this->sendRes('تم ازالة الصورة بنجاح', true);
        } else {
            return $this->sendRes('الصورة غير موجودة', false);
        }
    }
}
