<?php

namespace App\Http\Controllers;

use App\Services\QRCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QRCodeGeneratorController extends Controller
{
    public function generate(Request $request)
    {
        $data = (object)$request->validate([
            'text' => ['required', 'string', 'max:300'],
            'version' => ['required', 'numeric', 'in:2,3,4,5'],
            'scale' => ['required', 'numeric', 'in:1,2,3,4,5,10,15,20'],
        ]);

        $path = date('Y/m');
        Storage::makeDirectory('public/' . $path);
        $fullPath = storage_path('app/public/' . $path);

        if ($file = QRCodeGenerator::fromText(
            $data->text,
            $fullPath,
            $data->version,
            $data->scale,
        )) {
            return response()->json([
                'file' => asset(Storage::url("$path/$file")),
            ]);
        }
        return response()->json([
            'file' => '',
        ], 400);
    }
}
