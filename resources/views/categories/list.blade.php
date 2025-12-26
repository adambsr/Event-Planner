@extends('layouts.app')

@section('title', 'Categories - AAB Event Planner')

@section('content')
<div class="admin-container">
    <div class="admin-content">
        <div class="admin-header">
            <h1 class="page-title">Categories List</h1>
            @include('components.admin-nav')
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any() && old('name'))
            @php
                // Try to get category ID from URL if available
                $categoryId = request()->route('category') ? request()->route('category')->id : (old('category_id') ?: 0);
                $categoryName = old('name', '');
            @endphp
            @if($categoryId && $categoryName)
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        openEditModal({{ $categoryId }}, '{{ addslashes($categoryName) }}');
                    });
                </script>
            @endif
        @endif

        <!-- Categories Table Card -->
        <div class="events-table-card">
            <div class="table-header">
                <h2 class="table-title">Categories</h2>
                @can('create categories')
                    <button type="button" class="btn-create-event" onclick="openCreateModal()">Create category</button>
                @endcan
            </div>

            <div class="table-container">
                <table class="events-table">
                    <thead>
                        <tr>
                            <th>Category name</th>
                            <th>Events count</th>
                            <th class="actions-column"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>
                                    <span class="event-name">{{ $category->name }}</span>
                                </td>
                                <td>{{ $category->events_count ?? $category->events()->count() }}</td>
                                <td>
                                    @can('edit categories')
                                        <div class="action-menu" id="actionMenu{{ $category->id }}">
                                            <button class="action-menu-toggle" onclick="toggleActionMenu({{ $category->id }})">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8 9.5C8.82843 9.5 9.5 8.82843 9.5 8C9.5 7.17157 8.82843 6.5 8 6.5C7.17157 6.5 6.5 7.17157 6.5 8C6.5 8.82843 7.17157 9.5 8 9.5Z" fill="#667085"/>
                                                    <path d="M8 4.5C8.82843 4.5 9.5 3.82843 9.5 3C9.5 2.17157 8.82843 1.5 8 1.5C7.17157 1.5 6.5 2.17157 6.5 3C6.5 3.82843 7.17157 4.5 8 4.5Z" fill="#667085"/>
                                                    <path d="M8 14.5C8.82843 14.5 9.5 13.8284 9.5 13C9.5 12.1716 8.82843 11.5 8 11.5C7.17157 11.5 6.5 12.1716 6.5 13C6.5 13.8284 7.17157 14.5 8 14.5Z" fill="#667085"/>
                                                </svg>
                                            </button>
                                            <div class="action-menu-dropdown">
                                                <button type="button" class="action-menu-item" onclick="openEditModal({{ $category->id }}, '{{ addslashes($category->name) }}')">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.3333 2.00004C11.5084 1.82493 11.7163 1.68605 11.9444 1.5913C12.1726 1.49655 12.4166 1.44775 12.6629 1.44775C12.9092 1.44775 13.1532 1.49655 13.3814 1.5913C13.6095 1.68605 13.8174 1.82493 13.9925 2.00004C14.1676 2.17515 14.3065 2.3831 14.4012 2.61124C14.496 2.83938 14.5448 3.08341 14.5448 3.32971C14.5448 3.57601 14.496 3.82004 14.4012 4.04818C14.3065 4.27632 14.1676 4.48427 13.9925 4.65938L5.17583 13.476L1.33333 14.6667L2.524 10.8242L11.3333 2.00004Z" stroke="#344054" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                    Edit
                                                </button>
                                                @can('delete categories')
                                                    <form action="{{ route('categories.destroy', $category) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="action-menu-item archive">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M2.66667 4H13.3333M12.6667 4V12.6667C12.6667 13.0203 12.5262 13.3594 12.2761 13.6095C12.026 13.8596 11.6869 14 11.3333 14H4.66667C4.31305 14 3.97391 13.8596 3.72381 13.6095C3.47371 13.3594 3.33333 13.0203 3.33333 12.6667V4M5.33333 4V2.66667C5.33333 2.31305 5.47371 1.97391 5.72381 1.72381C5.97391 1.47371 6.31305 1.33333 6.66667 1.33333H9.33333C9.68696 1.33333 10.0261 1.47371 10.2762 1.72381C10.5263 1.97391 10.6667 2.31305 10.6667 2.66667V4" stroke="#D92D20" stroke-width="1.33333" stroke-linecap="round" stroke-linejoin="round"/>
                                                            </svg>
                                                            Delete
                                                        </button>
                                                    </form>
                                                @endcan
                                            </div>
                                        </div>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="empty-state">
                                    <p>No categories found. <a href="#" onclick="openCreateModal(); return false;">Create your first category</a></p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Create Category Modal -->
@can('create categories')
<div id="createModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Create Category</h2>
            <button class="modal-close" onclick="closeCreateModal()">&times;</button>
        </div>
        <form action="{{ route('categories.store') }}" method="POST" id="createCategoryForm">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category name</label>
                    <input 
                        type="text" 
                        name="name" 
                        class="form-input" 
                        placeholder="Enter category name"
                        required
                        maxlength="255"
                    >
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeCreateModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>
@endcan

<!-- Edit Category Modal -->
@can('edit categories')
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title">Edit Category</h2>
            <button class="modal-close" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="category_id" id="editCategoryId" value="">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Category name</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="editCategoryName"
                        class="form-input @error('name') is-invalid @enderror" 
                        placeholder="Enter category name"
                        required
                        maxlength="255"
                        value="{{ old('name') }}"
                    >
                    @error('name')
                        <span class="form-error">{{ $message }}</span>
                    @enderror
                    <span class="form-error" id="editCategoryError" style="display: none;"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </form>
    </div>
</div>
@endcan

@push('scripts')
<script>
// Create Modal Functions
function openCreateModal() {
    document.getElementById('createModal').classList.add('active');
    document.getElementById('createCategoryForm').reset();
    // Clear any previous errors
    const errorElements = document.querySelectorAll('#createCategoryForm .form-error');
    errorElements.forEach(el => el.textContent = '');
}

function closeCreateModal() {
    document.getElementById('createModal').classList.remove('active');
}

// Edit Modal Functions
function openEditModal(categoryId, categoryName) {
    document.getElementById('editModal').classList.add('active');
    document.getElementById('editCategoryName').value = categoryName;
    document.getElementById('editCategoryId').value = categoryId;
    document.getElementById('editCategoryForm').action = `/admin/categories/${categoryId}`;
    document.getElementById('editCategoryError').style.display = 'none';
    document.getElementById('editCategoryError').textContent = '';
    // Clear any validation classes
    document.getElementById('editCategoryName').classList.remove('is-invalid');
    // Close action menu
    document.querySelectorAll('.action-menu-dropdown').forEach(menu => {
        menu.classList.remove('active');
    });
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('active');
}

// Action Menu Functions
function toggleActionMenu(categoryId) {
    // Close all other menus
    document.querySelectorAll('.action-menu-dropdown').forEach(menu => {
        if (menu.id !== 'actionMenu' + categoryId + 'Dropdown') {
            menu.classList.remove('active');
        }
    });
    
    // Toggle current menu
    const menu = document.getElementById('actionMenu' + categoryId).querySelector('.action-menu-dropdown');
    menu.classList.toggle('active');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    if (!event.target.closest('.action-menu')) {
        document.querySelectorAll('.action-menu-dropdown').forEach(menu => {
            menu.classList.remove('active');
        });
    }
});

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    const createModal = document.getElementById('createModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target === createModal) {
        closeCreateModal();
    }
    if (event.target === editModal) {
        closeEditModal();
    }
});

</script>
@endpush
@endsection

