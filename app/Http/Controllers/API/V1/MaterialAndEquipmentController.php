<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\MaterialAndEquipment;
use Illuminate\Http\Request;

class MaterialAndEquipmentController extends Controller
{
    //

    public function listMaterialsAndEquipment()
    {
        try {
            $materialsAndEquipment = MaterialAndEquipment::all();
            return response()->json(['data' => $materialsAndEquipment], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching materials and equipment'], 500);
        }
    }
}
