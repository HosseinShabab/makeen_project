<<<<<<< HEAD
=======

>>>>>>> ba26b9e0ed7e0be0cc0aae8109ab597834cffbf5
<?php

namespace App\Http\Controllers;


use App\Models\Message;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function store(Request $request)
    {
        $type = $request->type;
        $typable_id = $request->typable_id;
        $collection = $request->collection;

        if ($type == 'users') {
            $user = User::find($typable_id);
            $user->find($request->id)->addMediaFromRequest('media')->toMediaCollection("$collection", 'local');
        } else if ($type == 'payments') {
            $payment = Payment::find($typable_id);
            $payment->find($request->id)->addMediaFromRequest('media')->toMediaCollection("$collection", 'local');
        } else if ($type == 'messages') {
            $message = Message::find($typable_id);
            $message->find($request->id)->addMediaFromRequest('media')->toMediaCollection("$collection", 'local');
        }
        return response()->json('uploaded');
    }

    public function delete(Request $request, string $id)
    {
        $id = $request->id;
        $media = Media::destroy($id);
        return response()->json($media);
    }

    public function download(Request $request)
    {
        $id = $request->id;
        $user = new Media();
        $user = $user->getMedia($id);
        return response()->download($user->getpath());
    }
}
<<<<<<< HEAD

=======
>>>>>>> ba26b9e0ed7e0be0cc0aae8109ab597834cffbf5
