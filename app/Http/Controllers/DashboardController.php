<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\WorkOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    //
    public function index(){
        $totalWorkOrders = WorkOrder::count();
        $notAssignedWorkOrders = WorkOrder::whereNull('assigned_at')->count();

        // Count the number of completed work orders
        $completedWorkOrders = WorkOrder::where('current_status', 'completed')->count();

        // Calculate the completion rate
        if ($totalWorkOrders > 0) {
            $completionRate = ($completedWorkOrders / $totalWorkOrders) * 100;
        } else {
            $completionRate = 0; // To handle the case when there are no work orders
    }
        $workOrders = WorkOrder::whereNotNull('assigned_at')->get();
        $totalResponseTime = 0;
        $totalCount = $workOrders->count();

        foreach ($workOrders as $workOrder) {
            // Calculate the response time for each work order
            $assignedAt = Carbon::parse($workOrder->assigned_at);
            $createdAt = Carbon::parse($workOrder->created_at);
            $responseTime = $assignedAt->diffInSeconds($createdAt);
            $totalResponseTime += $responseTime;
        }

        // Calculate the average response time
        if ($totalCount > 0) {
            $averageResponseTime = $totalResponseTime / $totalCount;
        } else {
            $averageResponseTime = 0; // To handle the case when there are no work orders
        }

        // Retrieve all completed work orders
        $completedWorkOrders = WorkOrder::where('current_status', 'completed')->get();

        // Calculate total revenue generated from completed work orders
        $totalRevenue = $completedWorkOrders->sum('total_amount');
        $activeClients = Client::where('status', 'active')->count();
        $latestWorkOrders = WorkOrder::latest()->take(5)->get();
        $openWorkOrders       = WorkOrder::where('current_status','pending')->count();
        //dd($openWorkOrders);
        $monthlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];  // or fetch dynamically
        $monthlyWorkOrdersData = WorkOrder::selectRaw('
                MONTH(created_at) as month,
                MONTHNAME(created_at) as month_name,
                COUNT(*) as total
            ')
            ->whereYear('created_at', now()->year) // only current year
            ->groupBy(DB::raw('MONTH(created_at)'), DB::raw('MONTHNAME(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        $monthlyWorkOrders = $monthlyWorkOrdersData->pluck('total');
        //$markers = DB::table('address')->select('lat', 'lng', 'name AS title')->get();





        return view('admin.dashboard', compact('latestWorkOrders','activeClients','totalRevenue','notAssignedWorkOrders','averageResponseTime','totalWorkOrders', 'completedWorkOrders', 'completionRate','openWorkOrders','monthlyLabels','monthlyWorkOrders',));


    }

}
