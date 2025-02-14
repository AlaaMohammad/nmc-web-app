<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\WorkOrder;
use App\Models\WorkOrderImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;

class WorkOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $workOrders = WorkOrder::all();
        return view('admin.work-orders.index', compact('workOrders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $clients = Client::all();
        $serviceCategories = ServiceCategory::all();
        //Generate Unique Work Order Number for each Work Order
        $latestWorkOrder = WorkOrder::latest('id')->first();
        $nextId = $latestWorkOrder ? $latestWorkOrder->id + 1 : 1;
        $date = now()->format('Ymd');

        $validated['wo_number'] = 'WO-' . $date . '-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        $workOrderNumber = $validated['wo_number'];
        return view('admin.work-orders.create', compact('clients','serviceCategories','workOrderNumber')  );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       //dd($request->all());
        $validator = Validator::make($request->all(), [
            'wo_number' => 'required|string',
            'service_category_id' => [
                'required',
                'exists:service_categories,id', // Ensure the service category exists
            ],
            'service_id' => [
                'required',
                'exists:services,id', // Ensure the service exists
                function ($attribute, $value, $fail) use ($request) {
                    // Validate that the selected service belongs to the selected service category
                    $serviceCategoryId = $request->input('service_category_id');
                    if (!\App\Models\Service::where('id', $value)->where('service_category_id', $serviceCategoryId)->exists()) {
                        $fail('The selected service does not belong to the selected service category.');
                    }
                },
            ],
            'client_description' => 'required|string|max:255',
            'scope' => 'required|string',
            'location' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'priority' => 'required|in:high,medium,low',
            'urgent' => 'required|boolean',
            'client_id' => 'required|exists:clients,id',
            'nte' => 'required|numeric|min:0',
            'due_date' => 'required|date|after_or_equal:today',
        ]);
        //if not validated return back with errors
        //dd($validator->errors());
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        //dd(storage_path('app/firebase/nmc-app-a515f-firebase-adminsdk-69vph-bd70525c8b.json'), file_exists(storage_path('app/firebase/nmc-app-a515f-firebase-adminsdk-69vph-bd70525c8b.json')));

        // Initialize Firebase Storage
        $credentialsPath = storage_path('app/firebase/nmc-app-a515f-firebase-adminsdk-69vph-bd70525c8b.json');

// Check if the file exists
        if (!file_exists($credentialsPath)) {
            throw new \Exception("Firebase credentials file not found at: {$credentialsPath}");
        }

// Initialize Firebase
        $firebase = (new Factory())->withServiceAccount($credentialsPath);        $storage = $firebase->createStorage();
        $bucket = $storage->getBucket();

        $uploadedImageUrls = [];

        // Upload each image to Firebase Storage
        foreach ($request->file('images') as $image) {
            $fileName = 'work-orders/' . uniqid() . '.' . $image->getClientOriginalExtension();
            $filePath = $image->getRealPath();

            $bucket->upload(fopen($filePath, 'r'), [
                'name' => $fileName,
            ]);

            // Generate a signed URL (valid for 1 year)
            $uploadedImageUrls[] = $bucket->object($fileName)->signedUrl(new \DateTime('+1 year'));
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        $workOrder = WorkOrder::create($request->all());
        foreach ($uploadedImageUrls as $url) {
            WorkOrderImage::create([
                'work_order_id' => $workOrder->id,
                'wo_image_path' => $url,
            ]);
        }

        return redirect()->route('work-orders.index')->with('success', 'Work Order Created Successfully');

    }

    /**
     * Display the specified resource.
     */
    public function show(WorkOrder $workOrder)
    {
        //
        $materials = $workOrder->materials;
       // dd($materials);
        return view('admin.work-orders.show', compact('workOrder','materials'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WorkOrder $workOrder)
    {
        //
        return view('admin.work-orders.edit', compact('workOrder'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WorkOrder $workOrder)
    {
        //
        dd($request->all());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WorkOrder $workOrder)
    {
        //
        $workOrder->delete();
        return redirect()->route('work-orders.index')->with('success', 'Work Order Deleted Successfully');
    }

    public function approveWorkOrder(WorkOrder $workOrder){
       // dd($workOrder);
        $workOrder->update(['approval_status' => 'approved']);

        $workOrder->workOrderLogs()->create([
            'action' => 'approved',
            'created_by' => 'Test Admin',
            'user_role' => 'admin'
        ]);
        return redirect()->back()->with('success', 'Work Order Approved');
    }
}
