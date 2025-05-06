<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Log;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaLibraryController extends Controller
{

    public $pagename = 'Media-Library';
    public function index(Request $request)
    {
        // dd($request);
        $collectionName = $request->c != null ? $request->c : 'default';
        $mediaCollections = $collectionName == 'default' ?  DB::table('media')->select('collection_name as name')->groupBy('collection_name')->get() : [];

        $model = DB::table('media')->select('model_type')->where('collection_name', $request->c)->first();

        $collectionMedia =  [];
        if ($model) {
            $items = app($model->model_type)::all();
            foreach ($items as $key => $item) {
                $media = $item->getMedia($collectionName);
                if (count($media) > 0) {
                    array_push($collectionMedia, $media[0]);
                }
            }
        }

        return Inertia::render('MediaLibrary/Index', ['collections' => $mediaCollections, 'allMedia' => $collectionMedia]);
    }

    public function download($id)
    {
        $mediaItem = Media::find($id);
        $logEntry = new Log();
        $logEntry->uid = Auth::user()->email;
        $logEntry->page = $this->pagename;
        $logEntry->function = 'DOWNLOAD';
        $logEntry->details = 'Complete';
        $logEntry->status = 1;
        $logEntry->save();
        return response()->download($mediaItem->getPath(), $mediaItem->file_name);
    }

    public function upload(Request $request)
    {
        $user = User::find(auth()->user()->id);
        try {
            if ($request->hasFile('file')) {
                $file = $user->addMedia($request->file('file'))->toMediaCollection($request->collection);
            }

            $logEntry = new Log();
            $logEntry->uid = Auth::user()->email;
            $logEntry->page = $this->pagename;
            $logEntry->function = 'UPLOAD';
            $logEntry->details = 'Complete';
            $logEntry->status = 1;
            $logEntry->save();
            return back();

            return redirect()->route('media.index', ['c' => $request->collection]);
        } catch (Exception $ex) {
            DB::rollback();
            DB::beginTransaction();
            $logEntry = new Log();
            $logEntry->uid = Auth::user()->email;
            $logEntry->page = $this->pagename;
            $logEntry->function = 'UPLOAD';
            $logEntry->details = $ex->getMessage();
            $logEntry->status = 0;
            $logEntry->save();
            DB::commit();
            return abort(500);
        }
    }

    public function delete(Request $request)
    {
        $mediaItem = Media::find($request->id);
        $mediaItem->delete();

        $logEntry = new Log();
        $logEntry->uid = Auth::user()->email;
        $logEntry->page = $this->pagename;
        $logEntry->function = 'DELETE';
        $logEntry->details = 'Complete';
        $logEntry->status = 1;
        $logEntry->save();
        return back();
    }
}
