<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\AttendanceVerificationCode;

class VerificationCodeGenerator extends Component
{
    public $verificationCode;
    public $showcode;

    public function generateCode()
    {
        // 1. 檢查是否已經有驗證碼存在，如果有則刪除它
        $existingCode = AttendanceVerificationCode::first();
        if ($existingCode) {
            $existingCode->delete();
        }

        // 2. 生成新的驗證碼
        $this->verificationCode = $this->generateVerificationCode();

        // 3. 將驗證碼存儲到資料庫
        AttendanceVerificationCode::create([
            'code' => $this->verificationCode,
        ]);
    }

    public function showCode()
    {
        $code = AttendanceVerificationCode::first();
        $this->showcode = $code ? $code->code : 'No Code Existing.';
    }

    private function generateVerificationCode()
    {
        // 在這裡實現你生成驗證碼的邏輯，例如使用 Laravel 的 Str::random() 生成隨機字符串
        return \Illuminate\Support\Str::random(6);
    }

    public function render()
    {
        return view('livewire.verification-code-generator');
    }
    
}