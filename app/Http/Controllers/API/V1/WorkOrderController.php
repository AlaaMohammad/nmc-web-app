<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Technician;
use App\Models\WorkOrder;
use App\Models\WorkOrderImage;
use App\Models\WorkOrderVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WorkOrderController extends Controller
{

    public function listWorkOrdersToday()
    {
        try {
            // Find the technician by ID
            $technician = Auth::guard('sanctum')->user();
            if (!$technician) {
                return response()->json(['error' => 'Technician not found'], 404);
            }

            // Fetch today's work orders assigned to the technician
            $todayWorkOrders = WorkOrder::with(['client', 'service', 'images'])
                ->where('assigned_to', $technician->id)
                ->whereDate('scheduled_at', Carbon::today())
                ->get();

            // Format today's work orders for the response
            $todayWorkOrders  = $todayWorkOrders->map(function ($workOrder) {
                return [
                    'id' => $workOrder->wo_number,
                    'date' => $workOrder->scheduled_at,
                    'service' => $workOrder->service->name ?? 'N/A', // Use the related service name
                    'location' => $workOrder->location, // Ensure the location field is properly populated
                ];
            });

            // Return the formatted data as JSON
            return response()->json([
                'todaySchedule' => $todayWorkOrders,
            ], 200);

        } catch (\Exception $e) {
            // Catch and return any unexpected errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function listNearbyWorkOrders($latitude, $longitude)
    {
        try {
            // Find the technician by ID
            $technician = Auth::guard('sanctum')->user();
            if (!$technician) {
                return response()->json(['error' => 'Technician not found'], 404);
            }

            $radiusInKm = 100; // Search radius in kilometers

            // Fetch nearby work orders using Haversine formula
            $nearbyWorkOrders = DB::select(
                "SELECT * FROM (
                SELECT id, wo_number, client_description, scheduled_at, current_status, (
                    6371 * acos(
                        cos(radians(?)) *
                        cos(radians(latitude)) *
                        cos(radians(longitude) - radians(?)) +
                        sin(radians(?)) *
                        sin(radians(latitude))
                    )
                ) AS distance
                FROM work_orders
            ) AS subquery
            WHERE distance <= ?
            ORDER BY distance ASC
            LIMIT 5",
                [$latitude, $longitude, $latitude, $radiusInKm] // Bind all placeholders
            );


            // Return the nearby work orders as JSON
            return response()->json([
                'nearbyWorkOrders' => $nearbyWorkOrders,
            ], 200);

        } catch (\Exception $e) {
            // Catch and return any unexpected errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function dashboard()
    {try {
        $technician = Technician::find(1); // Replace with actual technician ID
        if (!$technician) {
            return response()->json(['error' => 'Technician not found'], 404);
        }

        // Define technician location and search radius
        $technicianLatitude = 31.945400000000003; // Replace with actual latitude
        $technicianLongitude = 35.873468359375;  // Replace with actual longitude
        $radiusInKm = 10; // Search radius in kilometers

        // Fetch nearby work orders using Haversine formula
        $nearbyWorkOrders = DB::table('work_orders')
            ->select(
                'id',
                'wo_number',
                'client_description',
                'scheduled_at',
                'current_status',
                DB::raw("(
                6371 * acos(
                    cos(radians($technicianLatitude)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians($technicianLongitude)) +
                    sin(radians($technicianLatitude)) *
                    sin(radians(latitude))
                )
            ) AS distance")
            )
            ->having('distance', '<=', $radiusInKm)
            ->orderBy('distance', 'asc')
            ->take(5)
            ->get();

        // Fetch today's assigned work orders for the technician
        $todayWorkOrders = WorkOrder::with(['client', 'service', 'images'])
            ->where('assigned_to', $technician->id)
            ->whereDate('scheduled_at', Carbon::today())
            ->get();

        // Format nearby work orders
        $nearbyServices = $nearbyWorkOrders->map(function ($workOrder) {
            return [
                'id' => $workOrder->wo_number,
                'client' => $workOrder->client_description,
                'scheduled_at' => $workOrder->scheduled_at,
                'distance' => round($workOrder->distance, 1), // Round distance to 1 decimal place
                'image' => 'image_url', // Replace with actual logic to fetch the image URL
            ];
        });

        // Format today's work orders
        $todaySchedule = $todayWorkOrders->map(function ($workOrder) {
            return [
                'id' => $workOrder->wo_number,
                'date' => $workOrder->scheduled_at,
                'service' => $workOrder->service->name ?? 'N/A', // Use the related service name
                'location' => $workOrder->location, // Ensure the location field is properly populated
            ];
        });

        // Prepare the final response data
        $data = [
            'nearbyServices' => $nearbyServices,
            'technician' => [
                'id' => $technician->id,
                'name' => $technician->name,
                'location' => $technician->location,
            ],
            'todaySchedule' => $todaySchedule,
        ];

        // Return response as JSON
        return response()->json(['data' => $data], 200);

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
    }



    public function userSummery()
    {
        try {
        $technician = Auth::guard('sanctum')->user();
        $assignedWorkOrders = WorkOrder::where('assigned_to', $technician->id)->count();
        $inProcessWorkOrders = WorkOrder::where('assigned_to', $technician->id)->where('current_status', 'pending')->count();
        $completedWorkOrders = WorkOrder::where('assigned_to', $technician->id)->where('current_status', 'completed')->count();
        $data = [
            'technician' => $technician,
            'assigned_work_orders' => $assignedWorkOrders,
            'in_process_work_orders' => $inProcessWorkOrders,
            'completed_work_orders' => $completedWorkOrders
        ];
            return response()->json(['data' => $data], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
//    public function dashboard(){
//        try {
//            $technician = auth('sanctum')->user() ;
//            $assignedWorkOrders = WorkOrder::where('assigned_to', $technician->id)->count();
//            $inProcessWorkOrders = WorkOrder::where('assigned_to', $technician->id)->where('current_status', 'pending')->count();
//            $completedWorkOrders = WorkOrder::where('assigned_to', $technician->id)->where('current_status', 'completed')->count();
//            $location = $technician->location ?? 'Default Location'; // Replace with actual location logic
//            $availableJobs = WorkOrder::where('location', 'LIKE', '%' . $location . '%')
//                ->orWhere('skills', 'LIKE', '%' . $technician->skills . '%')
//                ->take(5) // Limit to 5 jobs
//                ->get(['id', 'wo_number','client_description', 'hourly_rate', 'location']);
//
//            $data = [
//                'technician' => $technician,
//                'assigned_work_orders' => $assignedWorkOrders,
//                'in_process_work_orders' => $inProcessWorkOrders,
//                'completed_work_orders' => $completedWorkOrders,
//                'available_jobs' => $availableJobs,
//            ];
//            return response()->json(['data' => $data], 200);
//        } catch (\Exception $e) {
//            return response()->json(['error' => $e->getMessage()], 500);
//        }
//    }

    public function listUnassignedWorkOrders()
    {
        try {
            $activeWorkOrders = WorkOrder::where('approval_status', 'approved')->where('assignment_status', 'unassigned')->with(['client', 'service'])->get();
            //data should be in this format id,logo,company,location,distance,job,description,type,rate
            //dd($activeWorkOrders);
            foreach ($activeWorkOrders as $activeWorkOrder) {
                $data[] = [
                    'id' => $activeWorkOrder->wo_number,
                    'logo' => $activeWorkOrder->client->logo_url,
                    'company' => $activeWorkOrder->client->name,
                    'location' => $activeWorkOrder->location,
                    'distance' => '2km',
                    'job' => $activeWorkOrder->service->name,
                    'description' => $activeWorkOrder->client_description,
                    'type' => $activeWorkOrder->service->name,
                    'rate' => 'N1000'
                ];
            }
            return response()->json(['data' => $data], 200);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function listAssignedWorkOrders()
    {
        try {
            $technician = auth('sanctum')->user();
           // dd($technician);
            $assignedWorkOrders = WorkOrder::where('assignment_status', 'assigned')
                ->where('assigned_to',  $technician->id)
                ->with('client')->get();
            foreach ($assignedWorkOrders as $assignedWorkOrder){
                $data[] = [
                    'id' => $assignedWorkOrder->wo_number,
                    'status' => $assignedWorkOrder->current_status,
                    'title' => $assignedWorkOrder->service->name,
                    'date' => $assignedWorkOrder->scheduled_at,
                    'logo' => $assignedWorkOrder->client->logo_url,
                    'company' => $assignedWorkOrder->client->name,
                    'location' => $assignedWorkOrder->location,
                    'distance' => '2km',
                    'job' => $assignedWorkOrder->service->name,
                    'description' => $assignedWorkOrder->client_description,
                    'type' => $assignedWorkOrder->service->name,
                    'rate' => 'N1000'
                ];
            }
            return response()->json(['data' => $data], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching assigned work orders'], 500);
        }
    }

    public function getWorkorderDetails($workorder){
        try{
            $workorder = WorkOrder::where('wo_number', $workorder)->with(['client','workOrderLogs'])->first();
            return response()->json(['data' => $workorder], 200);
        }catch (\Exception $e){
            return response()->json(['message' => 'Error fetching work order details'], 500);
        }
    }

    public function listInProcessWorkOrders($technicianId=1)
    {
        try {
            $inProcessWorkOrders = WorkOrder::where('assigned_to',$technicianId)->with('client')->with()->get();
            return response()->json(['data' => $inProcessWorkOrders], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching in process work orders'], 500);
        }
    }

    public function listCompletedWorkOrders($technicianId=1)
    {
        try {
           $technicianCompletedWorkOrders = WorkOrder::where('status', 'completed')->where('technician_id', $technicianId)->with('client')->get();
            return response()->json(['data' => $technicianCompletedWorkOrders], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error fetching completed work orders'], 500);
        }
    }
    public function saveWorkOrderImage($workOrder , Request $request)
    {
        try {
            // Get the file path from the request
            $workOrderImage = new WorkOrderImage();
            $workOrderImage->work_order_id = WorkOrder::where('wo_number',$workOrder)->first()->id;
            $workOrderImage->wo_image_path = $request->input('url');
            $workOrderImage->save();
            return response()->json(['message' => 'Image saved successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function saveWorkOrderTechnicianReport($workOrderId , Request $request)
    {
        try {
            // Get the report from the request
            $report = $request->input('report');
            $workOrder = WorkOrder::where('wo_number',$workOrderId)->first();
            $workOrder->technician_report = $report;
            $workOrder->problem_reported = 'yes';
            $workOrder->save();
            return response()->json(['message' => 'Report saved successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function saveWorkOrderMaterials($workOrderId , Request $request)
    {
        try {
            // Get the material from the request
            $materials = $request->input('materials');
            $technician = auth('sanctum')->user();
            $workOrder = WorkOrder::where('wo_number',$workOrderId)->first();

            $workOrder->internal_status = 'Check In';
            $workOrder->current_status = 'checked in';
            $workOrder->save();
            if (!empty($materials)) {
                foreach ($materials as $material) {
                    $workOrder->materials()->attach(1, ['material_name' => $material['name'] ?? 'No Material']);
                }
            }
            $workOrder->workOrderLogs()->create([
                'action' => 'material added',
                'created_by' => Technician::where('id', $technician->id)->first()->full_name,
                'user_role' => 'technician'
            ]);
            return response()->json(['message' => 'Material saved successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function checkInWorkOrder($workOrder)
    {
        try {
            $technician = auth('sanctum')->user();
            $workOrder = WorkOrder::where('wo_number',$workOrder)->first();
            $workOrder->current_status = 'checked in';
            $workOrder->save();
            $workOrder->workOrderLogs()->create([
                'visit_number' => time(),
                'action' => 'checked_in',
                'created_by' => Technician::where('id', $technician->id)->first()->full_name,
                'user_role' => 'technician'
            ]);
            $workOrder->workOrderVisits()->create([
                'visit_number' => time(),
                'checkin_time' => now(),
                'checked_in_by' => Technician::where('id', $technician->id)->first()->full_name,
            ]);
            return response()->json(['message' => 'Work order checked in successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function checkOutWorkOrder($workOrderId)
    {
        try {
            $technician = auth('sanctum')->user();
            $workOrder = WorkOrder::where('wo_number',$workOrderId)->first();
            $workOrder->current_status = 'checked out';
            $workOrder->save();
            //dd($workOrder  );
            $workOrder->workOrderLogs()->create([
                'action' => 'checked_out',
                'created_by' => Technician::where('id', $technician->id)->first()->full_name,
                'user_role' => 'technician'
            ]);

            $workOrderVisit = WorkOrderVisit::where('work_order_id', $workOrder->id)->latest();
           // dd($workOrderVisit->first());
            $workOrderVisit->update([
                'checkout_time' => now(),
                'duration' => now()->diffInHours($workOrderVisit->first()->checkin_time),
                'checked_out_by' => Technician::where('id', $technician->id)->first()->full_name,
            ]);
            return response()->json(['message' => 'Work order checked out successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function assignWorkOrder($workOrderId , Request $request)
    {
        try {
            $technician = Auth::guard('sanctum')->user();
            return response()->json(['message' => 'Work order assigned successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error assigning work order'], 500);
        }
    }

    public function acceptWorkOrder(Request $request,$workOrderId)
    {
        try {
            // Get the work order from the request then save in the logs
            $workOrder = WorkOrder::where('wo_number',$workOrderId)->first();
            if (!$workOrder) {
                return response()->json(['message' => 'Work order not found'], 404);
            }
            $workOrder->assignment_status = 'assigned';

            $technician = auth('sanctum')->user() ;

            $workOrder->assigned_to = $technician->id;
            $workOrder->assigned_at = $request->has('date') ? $request->date : now();
            $workOrder->save();
            $workOrder->workOrderLogs()->create([
                'action' => 'assigned',
                'created_by' => Technician::where('id', $technician->id)->first()->full_name,
                'user_role' => 'technician'
            ]);
            return response()->json(['message' => 'Work order accepted successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function rejectWorkOrder($workOrderId)
    {
        try {
            $workOrder = WorkOrder::find($workOrderId);
            $workOrder->assignment_status = 'unassigned';
            $workOrder->save();
            return response()->json(['message' => 'Work order rejected successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error rejecting work order'], 500);
        }
    }

    public function startWorkOrder($workOrderId)
    {
        try {
            $workOrder = WorkOrder::find($workOrderId);
            $workOrder->current_status= 'in_progress';
            $workOrder->save();
            return response()->json(['message' => 'Work order started successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e], 500);
        }
    }



    public function completeWorkOrder($workOrderId , Request $request)
    {
        try {
            $workOrder = WorkOrder::where('wo_number',$workOrderId)->first();
            $technician = Auth::guard('sanctum')->user();
            $workOrder->current_status = 'completed';
            $workOrder->save();
            $workOrderVisit = WorkOrderVisit::where('work_order_id', $workOrder->id)->latest();
            $workOrderVisit->update([
                'checkout_time' => now(),

                'duration' => now()->diffInHours($workOrderVisit->first()->checkin_time),
                'checked_out_by' => Technician::where('id', $technician->id)->first()->full_name,
            ]);


            return response()->json(['message' => 'Work order completed successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error completing work order'], 500);
        }
    }

    public function successWorkOrder($workOrderId){
        try {
            $workOrder = WorkOrder::find($workOrderId);
            $workOrder->internal_status = 'Check In';
            $workOrder->current_status = 'checked in';
            $workOrder->checkin_time = now();
            $workOrder->save();
            $workOrder->workOrderLogs()->create([
                'action' => 'checked_in',
                'created_by' => Technician::where('id', $this->technitionId)->first()->full_name,
                'user_role' => 'technician'
            ]);
            return response()->json(['message' => 'Work order success successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e], 500);
        }

    }

    public function failWorkOrder($workOrderId){
        try {
            $workOrder = WorkOrder::find($workOrderId);
            $workOrder->internal_status = 'Check Out';
            $workOrder->current_status = 'checked out';
            $workOrder->checkout_time = now();
            $workOrder->save();
            $workOrder->workOrderLogs()->create([
                'action' => 'checked_out',
                'created_by' => 'Technician',
                'user_role' => 'technician'
            ]);
            return response()->json(['message' => 'Work order failed successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e], 500);
        }

    }

    public function revisitWorkOrder($workOrderId, Request $request){
        try {
            $workOrder = WorkOrder::where('wo_number',$workOrderId)->first();
            $workOrder->current_status = 'revisit';
            $workOrder->save();
            $workOrder->workOrderLogs()->create([
                'action' => 'CheckOut - Revisit',
                'created_by' => Technician::where('id', $this->technitionId)->first()->full_name,
                'user_role' => 'technician'
            ]);
            $workOrder->workOrderRequest()->create([
                'request_type' => 'revisit',
                'request_description' => $request->input('request_description'),
                'created_by' => Technician::where('id', $this->technitionId)->first()->full_name,
            ]);
            return response()->json(['message' => 'Work order revisit submitted successfully'], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => $e], 500);
        }

    }

}
