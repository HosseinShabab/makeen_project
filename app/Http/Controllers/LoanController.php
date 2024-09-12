<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoanReqeust;
use App\Http\Requests\MessageRequest;
use App\Models\Installment;
use App\Models\Loan;
use App\Models\Message;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\LoanFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Monolog\Handler\Slack\SlackRecord;
use stdClass;


class LoanController extends Controller
{
    private function isStoreAble($user_id, $guarantors_id)
    {
        $guarantors_setting = Setting::find('1');
        $guarantors_count = $guarantors_setting->guarantors_count;
        if ($guarantors_count != sizeof($guarantors_id)) return false;
        $loans_count = $guarantors_setting->loans_count;
        $user_loans = Loan::where([['user_id', $user_id], ['status', 'unpaid'], ['admin_accept', "!=", 'faild']])->count();
        if ($user_loans >= $loans_count) return false;
        foreach ($guarantors_id as $guarantor_id) {
            $guarantor = User::find($guarantor_id);
            if (!$guarantor || !$guarantor->can('active') || $guarantor_id == $user_id) return false;
        }
        return $user_loans;
    }
    public function requestCnt()
    {
        $loans = Loan::where([['guarantors_accept', 'accepted'], ['admin_accept', 'pending']])->count();
        return response()->json(['loans'=>$loans]);
    }

    public function showGuarantors(Request $request)
    {
        $user = User::select('id', 'first_name', 'last_name')->where('national_code', $request->national_code)->first();
        if (!$user) {
            return response()->json(["error"=>"guarantor not found"]);
        }
        if ($user->id == auth()->user()->id || !$user->can('active')) {
            return response()->json(["error"=>"guarantor is not worthy"]);
        }
        return response()->json(['id' => $user->id, 'name' => $user->first_name . ' ' . $user->last_name]);
    }

    public function acceptGuarantor(Request $request)
    {
        $loan = Loan::find($request->loan_id);
        $loan_guarantor = DB::table('loan_guarantor')
            ->where("loan_id", $request->loan_id)->where('guarantor_id', $request->user()->id)->first();
        if (!$loan_guarantor || !$loan) {
            return response()->json(['error'=>"loan_id not valid "]);
        }
        if ($loan_guarantor->guarantor_accept != "pending") {
            return response()->json(['error'=>"you have already voted"]);
        }

        $loan_guarantor = DB::table('loan_guarantor')
            ->where("loan_id", $request->loan_id)->where('guarantor_id', $request->user()->id)->update(["guarantor_accept" => $request->guarantor_accept]);
        $guarnators_accept = DB::table('loan_guarantor')
            ->select('guarantor_accept')->where('loan_id', $request->loan_id)->get();
        $temp = "accepted";

        foreach ($guarnators_accept as $guarantor_accept) {

            if ($guarantor_accept->guarantor_accept == "pending")
                $temp = "pending";
            if ($guarantor_accept->guarantor_accept == "faild") {
                $temp = "faild";
                break;
            }
        }


        $loan = Loan::find($request->loan_id);
        if ($temp == "accepted") {
            $loan->guarantors_accept = $temp;
        }
        if ($temp == 'faild') {
            // yek payam baraye sahebe loan ke in rad karde update kon
            $message = new MessageRequest();
            $message->user_id = $loan->user_id;
            $message->type = "systemic";
            $message->title = "guarantor_failed";
            $message->description = "با سلام با درخواست شما از سمت ضامن رد شد ";
            app(MessageController::class)->storeAdmin($message);
        }
        $loan->save();

        return response()->json(['success'=>'succsseded']);
    }

    public function showAdmin(Request $request)
    {
        if ($request->count == "checked") {

            $loans = Loan::with('user:id,first_name,last_name','guarantors')->where('guarantors_accept', '!=', 'faild')->where('admin_accept', "!=", "pending")->where('type', $request->type)->get();
        } else if ($request->count == "all") {

            $loans = Loan::with('user:id,first_name,last_name','guarantors')->where('admin_accept', "pending")->where('type', $request->type)->get();
        }
        if(!$request->count) return response()->json(["error"=>'count can not be null ']);
        return response()->json(['loans'=>$loans]);
    }

    public function show(Request $request)
    {
        $user_id = $request->user()->id;
        $loans = Loan::with('guarantors')->where([['user_id', $user_id],['admin_accept',$request->admin_accept]])->get();

        return response()->json(['loans'=>$loans]);
    }

    public function acceptAdmin(Request $request)
    {
        $loan = Loan::find($request->loan_id);
        if(!$loan) return response()->json(['error'=>'loan doesnt exist']);
        if ($loan->admin_accept != "pending") {
            return response()->json(['error' => 'you have arlready voted']);
        }
        $loan->admin_accept = $request->admin_accept;
        $loan->admin_description = $request->admin_description;

        if ($request->admin_accept == "accepted") {
            $temp = $request->installment_count;
            $installment_price = $request->loan_price;
            $installment_price /= $temp;

            for ($i = 1; $i <= $temp; $i++) {
                $due_date = Carbon::now()->addMonths($i)->toDateString();
                $installment = new Installment();
                $installment = $installment->create([
                    "type" => "installment",
                    "count" => $i,
                    "price" => $installment_price,
                    "due_date" => $due_date,
                    "loan_id" => $loan->id,
                    'user_id' => $loan->user_id,
                ]);
            }
            $reqloans = Loan::where([['user_id', $loan->user_id], ['admin_accept', 'pending']])->get();
            foreach ($reqloans as $reqloan) {
                $reqloan->count++;
                $reqloan->save();
            }
        }
        $loan->save();
        return response()->json(['loan'=>$loan]);
    }

    public function loanDetails()
    {
        $user = auth()->user();
        $user_loans = Loan::where([['user_id', $user->id], ['status', 'unpaid'], ['admin_accept', "!=", 'faild']])->count();
        return response()->json(['count' => $user_loans, 'date' => Carbon::now()->toDateString()]);
    }
    public function store(LoanReqeust $request)
    {
        $user_id = $request->user()->id;
        $guarantors_id = $request->guarantors_id;
        $count = $this->isStoreAble($user_id, $guarantors_id);
        if (! $count) return response()->json(['error'=>'you have reached the limit or gourantors not valid']);

        $loan = new Loan();
        $loan = $loan->create([
            "loan_number" => $count + 1,
            "price" => $request->price,
            "user_description" => $request->user_description,
            "type" => $request->type,
            "user_id" => $user_id,
        ]);
        $user = User::find($user_id);
        foreach ($guarantors_id as $guarantor_id) {
            $guarantor = User::find($guarantor_id);
            $guarantor_name = $guarantor->first_name . ' ' . $guarantor->last_name;
            DB::table("loan_guarantor")->insert(["loan_id" => $loan->id, "guarantor_id" => $guarantor_id, 'guarantor_name' => $guarantor_name]);
            //yek massage sakhte beshe baraye on user :
            $message = new MessageRequest();
            $message->user_id = $guarantor_id;
            $message->type = "systemic";
            $message->title = "loan_request";
            $message->description = "$request->price برای وام به مبلغ  $user->first_name.' '. $user->last_name درخواست ضمانت از طرف ";
            app(MessageController::class)->storeAdmin($message);
        }

        return response()->json(['loan'=>$loan]);
    }

    public function updateGuarantor(Request $request)
    {

        $guarantor = User::find($request->new_guarantor_id);
        if (!$guarantor || !$guarantor->can('active') || $guarantor->id == $request->user()->id) return  response()->json(['error' => 'guarantor not worthy']);
        $guarantor_name = $guarantor->first_name . ' ' . $guarantor->last_name;
        $last_guarantor = DB::table('loan_guarantor')->where('guarantor_id', $request->last_guarantor_id)->delete();
        $new_guarantor = DB::table('loan_guarantor')->insert([
            'guarantor_id' => $request->new_guarantor_id,
            'guarantor_name' => $guarantor_name,
            'loan_id' => $request->loan_id,
        ]);
        //yek payam besas;
        return response()->json(['new_guarantor'=>$new_guarantor]);
    }
}
