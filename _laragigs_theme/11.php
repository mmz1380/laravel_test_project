<?php

namespace _laragigs_theme;

use App\Http\Controllers\Controller;
use App\Models\VerificationCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Mockery\Exception;

class Death extends Controller
{
    public function create()
    {
        echo 'sadsd';
        return view('otp.1');
    }

    private function generateOtp(Request $request)
    {

        # User Does not Have Any Existing OTP
        $verificationCode = VerificationCode::where('mobile_no', $request->mobile_no)->latest()->first();

        $now = Carbon::now();

        if($verificationCode && $now->isBefore($verificationCode->expire_at)){
            return $verificationCode;
        }

        // Create a New OTP
        return VerificationCode::create([
            'otp' => rand(100000, 999999),
            'mobile_no' => $request->mobile_no,
            'expire_at' => Carbon::now()->addMinutes(2)
        ]);

    }

    private function sendOTP(VerificationCode $verificationCode)
    {
//        $verificationCode = "sssss";
        $url = 'https://console.melipayamak.com/api/send/shared/86677eb9fe2f4eeba221044ec8e1cab8';
        $data = array('bodyId' => 148416, 'to' => "{$verificationCode->mobile_no}", 'args' => ["{$verificationCode->otp}"]);
        $data_string = json_encode($data);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);

// Next line makes the request absolute insecure
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
// Use it when you have trouble installing local issuer certificate
// See https://stackoverflow.com/a/31830614/1743997

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER,
            array('Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        curl_close($ch);
// to debug
        if(curl_errno($ch)){
            throw new Exception(message: "Cannot Send OTP, Try again!");
        }
    }

    private function destroy(VerificationCode $verificationCode)
    {
        $verificationCode->delete();
    }

    public function store(Request $request)
    {
        $formField = $request->validate([
            'mobile_no' => ['required', 'min:8', Rule::unique('users', 'email')],
        ]);


        $verificationCode = $this->generateOtp($request);
        try {
            $this->sendOTP($verificationCode);
        }
        catch (Exception $ex) {
            $this->destroy($verificationCode);
            return redirect('/otp/1')->with('message', "{$ex->getMessage()}");
        }
//        ddd($verificationCode);
        return redirect('/otp/2')->with('verificationCode', $verificationCode->id);
    }

    public function valid(Request $request)
    {
//        ddd(session('verificationCode'));
//        return view('otp.2',[
//            'verificationCode' => $verificationCode,
//        ]);
        return view('otp.2');
    }

    public function validateOTP(Request $request, VerificationCode $verificationCode)
    {
//        ddd(session());
//        ddd(VerificationCode::where('mobile_no', session('number'))->latest()->first());
//        $verificationCode = VerificationCode::where('mobile_no', $request->mobile_no)->latest()->first();
//        ddd($verificationCode);
        $now = Carbon::now();
        $formField = $request->validate([
            'otp' => ['required','min:6'],
        ]);
        if ($request->otp == $verificationCode->otp && $now->isBefore($verificationCode->expire_at) ) {
            return redirect('/login');
        }
        elseif ($now->isAfter($verificationCode->expire_at)) {
            return redirect('/otp/1')->with('message', "Your OTP is expired");
        }
        else{
            return redirect('/otp/2')->with('message', "Your OTP is incorrect!");
        }

    }
}
