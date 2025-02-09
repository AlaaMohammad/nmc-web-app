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

        return view('admin.dashboard', compact('latestWorkOrders','activeClients','totalRevenue','notAssignedWorkOrders','averageResponseTime','totalWorkOrders', 'completedWorkOrders', 'completionRate'));


    }

}
