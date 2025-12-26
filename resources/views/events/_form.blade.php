{{-- Reusable form partial for create and edit --}}

<form action="{{ $event->id ? route('events.update', $event) : route('events.store') }}" method="POST" enctype="multipart/form-data" class="event-form">
    @csrf
    @if($event->id)
        @method('PUT')
    @endif

    <!-- Event Details Section -->
    <div class="form-section">
        <h2 class="section-title">{{ $event->id ? 'Edit Event' : 'Create Event' }}</h2>
        
        <div class="form-grid">
            <!-- Event Title -->
            <div class="form-group full-width">
                <label class="form-label">Event Title</label>
                <input 
                    type="text" 
                    name="title" 
                    value="{{ old('title', $event->title ?? '') }}"
                    placeholder="Title" 
                    required
                    class="form-input"
                >
            </div>

            <!-- Category -->
            <div class="form-group {{ $event->id ? '' : 'full-width' }}">
                <label class="form-label">Category</label>
                <div class="select-wrapper">
                    <select name="category_id" required class="form-input form-select">
                        <option value="">Select category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $event->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="select-arrow">▼</span>
                </div>
            </div>

            <!-- Start Date -->
            <div class="form-group">
                <label class="form-label">Start date</label>
                <input 
                    type="datetime-local" 
                    name="start_date" 
                    value="{{ old('start_date', $event->id && $event->start_date ? $event->start_date->format('Y-m-d\TH:i') : '') }}"
                    required
                    class="form-input"
                    placeholder="date"
                >
            </div>

            <!-- End Date -->
            <div class="form-group">
                <label class="form-label">End date</label>
                <input 
                    type="datetime-local" 
                    name="end_date" 
                    value="{{ old('end_date', $event->id && $event->end_date ? $event->end_date->format('Y-m-d\TH:i') : '') }}"
                    required
                    class="form-input"
                    placeholder="date"
                >
            </div>

            <!-- Place -->
            <div class="form-group">
                <label class="form-label">Place</label>
                <input 
                    type="text" 
                    name="place" 
                    value="{{ old('place', $event->place ?? '') }}"
                    placeholder="Place" 
                    required
                    class="form-input"
                >
            </div>

            <!-- Capacity -->
            <div class="form-group">
                <label class="form-label">Capacity</label>
                <input 
                    type="number" 
                    name="capacity" 
                    value="{{ old('capacity', $event->capacity ?? '') }}"
                    placeholder="Capacity" 
                    required
                    min="1"
                    class="form-input"
                >
            </div>

            <!-- Pricing -->
            <div class="form-group">
                <label class="form-label">Pricing</label>
                <div class="select-wrapper">
                    <select name="pricing_type" id="pricing_type" class="form-input form-select" onchange="togglePriceInput()">
                        @php
                            $isFree = old('pricing_type') ? (old('pricing_type') == 'free') : ($event->id ? $event->is_free : true);
                        @endphp
                        <option value="free" {{ $isFree ? 'selected' : '' }}>Free Access</option>
                        <option value="paid" {{ !$isFree ? 'selected' : '' }}>Paid</option>
                    </select>
                    <span class="select-arrow">▼</span>
                </div>
            </div>

            <!-- Amount (shown only when Paid is selected) -->
            <div class="form-group" id="price_input_group" style="display: {{ old('pricing_type') ? (old('pricing_type') == 'paid' ? 'block' : 'none') : ($event->id && !$event->is_free ? 'block' : 'none') }};">
                <label class="form-label">Amount</label>
                <input 
                    type="number" 
                    name="price" 
                    value="{{ old('price', $event->price ?? '') }}"
                    placeholder="Amount" 
                    step="0.01"
                    min="0"
                    class="form-input"
                    id="price_input"
                    {{ !$isFree && !old('pricing_type') ? 'required' : '' }}
                >
            </div>
        </div>
    </div>

    <!-- Event Description Section -->
    <div class="form-section">
        <h2 class="section-title">Event Description</h2>

        <!-- Event Image -->
        <div class="form-group full-width">
            <label class="form-label">Event Image</label>
            <div class="image-upload-area" id="imageUploadArea">
                <input 
                    type="file" 
                    name="image" 
                    id="image_input"
                    accept="image/*"
                    class="image-input"
                    onchange="previewImage(this)"
                >
                @if($event->id && $event->image)
                    <img src="{{ asset('storage/' . $event->image) }}" id="imagePreview" class="image-preview">
                    <div class="image-upload-placeholder" id="imagePlaceholder" style="display: none;">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <p>Click to upload or drag and drop</p>
                        <p class="image-hint">PNG, JPG, GIF up to 2MB</p>
                    </div>
                @else
                    <div class="image-upload-placeholder" id="imagePlaceholder">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                            <polyline points="17 8 12 3 7 8"></polyline>
                            <line x1="12" y1="3" x2="12" y2="15"></line>
                        </svg>
                        <p>Click to upload or drag and drop</p>
                        <p class="image-hint">PNG, JPG, GIF up to 2MB</p>
                    </div>
                    <img id="imagePreview" class="image-preview" style="display: none;">
                @endif
            </div>
        </div>

        <!-- Event Description -->
        <div class="form-group full-width">
            <label class="form-label">Event Description</label>
            <textarea 
                name="description" 
                placeholder="Type here..." 
                required
                class="form-input form-textarea"
                rows="6"
            >{{ old('description', $event->description ?? '') }}</textarea>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="form-actions">
        <button type="submit" class="btn-submit-large">{{ $event->id ? 'Update event' : 'Create event' }}</button>
    </div>
</form>

@push('scripts')
<script>
function togglePriceInput() {
    const pricingType = document.getElementById('pricing_type').value;
    const priceInputGroup = document.getElementById('price_input_group');
    const priceInput = document.getElementById('price_input');
    
    if (pricingType === 'paid') {
        priceInputGroup.style.display = 'block';
        priceInput.setAttribute('required', 'required');
    } else {
        priceInputGroup.style.display = 'none';
        priceInput.removeAttribute('required');
        priceInput.value = '';
    }
}

function previewImage(input) {
    const file = input.files[0];
    const placeholder = document.getElementById('imagePlaceholder');
    const preview = document.getElementById('imagePreview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
            if (placeholder) placeholder.style.display = 'none';
        };
        reader.readAsDataURL(file);
    } else {
        if (preview) preview.style.display = 'none';
        if (placeholder) placeholder.style.display = 'flex';
    }
}

// Handle form submission to set is_free
document.querySelector('.event-form').addEventListener('submit', function(e) {
    const pricingType = document.getElementById('pricing_type').value;
    const priceInput = document.getElementById('price_input');
    
    if (pricingType === 'free') {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'is_free';
        hiddenInput.value = '1';
        this.appendChild(hiddenInput);
        
        if (priceInput) {
            priceInput.value = '0';
        }
    } else {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'is_free';
        hiddenInput.value = '0';
        this.appendChild(hiddenInput);
    }
});
</script>
@endpush
