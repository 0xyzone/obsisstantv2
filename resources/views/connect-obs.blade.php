
@php
    $userId = auth()->user()->id;
    $setting = App\Models\ObsSetting::where('user_id', $userId)->first();
    // dd($setting);
    $password = Illuminate\Support\Facades\Crypt::decryptString($setting->password);
@endphp
