@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <h2>Create New Work Order</h2>
        <form action="{{ route('work-orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="wo_number" class="form-label">WO #</label>
                <input type="text"
                       class="form-control @error('wo_number') is-invalid @enderror"
                       id="wo_number"
                       name="wo_number"
                       value="{{ old('wo_number', $workOrderNumber) }}"
                       readonly>
                @error('wo_number')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            <div class="row mb-3">
                <!-- Service Category Dropdown -->
                <div class="col-md-6">
                    <label for="service_category" class="form-label">Service Category</label>
                    <select class="form-select @error('service_category_id') is-invalid @enderror" id="service_category" name="service_category_id" required>
                        <option value="">Select a Service Category</option>
                        @foreach ($serviceCategories as $category)
                            <option value="{{ $category->id }}" {{ old('service_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('service_category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Service Dropdown -->
                <div class="col-md-6">
                    <label for="service" class="form-label">Service</label>
                    <select class="form-select @error('service_id') is-invalid @enderror" id="service" name="service_id" required disabled>
                        <option value="">Select a Service</option>
                        <!-- Services will be populated dynamically -->
                    </select>
                    @error('service_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="client_description" rows="3" required>{{ old('description') }}</textarea>
                @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="scope" class="form-label">Scope</label>
                <textarea class="form-control @error('scope') is-invalid @enderror" id="scope" name="scope" rows="3" required>{{ old('scope') }}</textarea>
                @error('scope')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location') }}" required>
                @error('location')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <div id="map-location" style="width: 100%; height: 400px;"></div>
                <input type="hidden" name="latitude" id="latitude" value="{{ old('latitude') }}">
                <input type="hidden" name="longitude" id="longitude" value="{{ old('longitude') }}">
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Priority</label>
                <select class="form-select @error('priority') is-invalid @enderror" id="priority" name="priority" required>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
                @error('priority')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="urgent" class="form-label">Urgent</label>
                <select class="form-select @error('urgent') is-invalid @enderror" id="urgent" name="urgent" required>
                    <option value="0" {{ old('urgent') == '0' ? 'selected' : '' }}>No</option>
                    <option value="1" {{ old('urgent') == '1' ? 'selected' : '' }}>Yes</option>
                </select>
                @error('urgent')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="client" class="form-label">Client</label>
                <select class="form-select @error('client') is-invalid @enderror" id="client" name="client_id" required>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}" {{ old('client') == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                    @endforeach
                </select>
                @error('client')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="nte">NTE($)</label>
                <input type="number" class="form-control @error('nte') is-invalid @enderror" id="nte" name="nte" value="{{ old('nte') }}" required>
                @error('nte')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="due-date" class="form-label">Due Date</label>
                <input type="datetime-local"
                       class="form-control @error('due_date') is-invalid @enderror"
                       id="due-date"
                       name="due_date"
                       value="{{ old('due_date') }}"
                       min="{{ now()->format('Y-m-d\TH:i') }}"
                       required>
                @error('due_date')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="images" class="form-label">Upload Images</label>
                <input type="file" class="form-control" id="images" name="images[]" multiple required>
            </div>

            <button type="submit" class="btn btn-primary">Create Work Order</button>
        </form>
    </div>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDF_LqV1BTVxwzreBZZR6kfqtEAqAjd9b4"></script>
    <script>
        let map;
        let marker;

        function initMap() {
            const initialLat = parseFloat("{{ old('latitude', 31.9454) }}");
            const initialLng = parseFloat("{{ old('longitude', 35.9284) }}");
            const initialLocation = { lat: initialLat, lng: initialLng };

            map = new google.maps.Map(document.getElementById("map-location"), {
                center: initialLocation,
                zoom: 8,
            });

            marker = new google.maps.Marker({
                position: initialLocation,
                map: map,
                draggable: true,
            });

            // Update hidden input fields when the marker is dragged
            marker.addListener("dragend", (event) => {
                document.getElementById("latitude").value = event.latLng.lat();
                document.getElementById("longitude").value = event.latLng.lng();
            });

            // Update hidden input fields on map click
            map.addListener("click", (event) => {
                marker.setPosition(event.latLng);
                document.getElementById("latitude").value = event.latLng.lat();
                document.getElementById("longitude").value = event.latLng.lng();
            });
        }

        window.onload = initMap;
    </script>
    <script>
        document.getElementById('service_category').addEventListener('change', function () {
            const categoryId = this.value;
            const serviceSelect = document.getElementById('service');

            // Clear previous options
            serviceSelect.innerHTML = '<option value="">Select a Service</option>';

            // Disable the dropdown if no category is selected
            if (!categoryId) {
                serviceSelect.disabled = true;
                return;
            }

            // Fetch services for the selected category
            fetch(`/categories/${categoryId}/services`)
                .then(response => response.json())
                .then(data => {
                    if (data.services && data.services.length > 0) {
                        // Populate the dropdown with the fetched services
                        data.services.forEach(service => {
                            const option = document.createElement('option');
                            option.value = service.id;
                            option.textContent = service.name;
                            serviceSelect.appendChild(option);
                        });
                        serviceSelect.disabled = false;
                    } else {
                        serviceSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error fetching services:', error);
                    serviceSelect.disabled = true;
                });
        });
    </script>
@endsection
