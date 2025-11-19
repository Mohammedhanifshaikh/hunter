@extends('layouts.master')
@section('title', ($mode === 'add' ? 'Add Plan' : 'Edit Plan') . ' - CPanel')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $mode == 'add' ? 'Add Plan' : 'Edit Plan' }}</h5>

                </div>
                <div class="card-body">
                    @if ($mode === 'add')
                    <form id="addCategoryForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label" for="plan_name">Plan Name</label>
                            <input type="text" class="form-control fix-word-first-letter" id="plan_name" name="plan_name"
                                placeholder="Leminar Air Flow" />
                            <span class="text-danger" id="plan_name_error"></span>
                        </div>



                        <div class="col-md">
                            <small class="form-label fw-medium d-block">Status</small>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status" value="1" />
                                <label class="form-check-label" for="status">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" checked type="radio" name="status" id="status"
                                    value="0" />
                                <label class="form-check-label" for="status">Inactive</label>
                            </div>
                            <span class="text-danger" id="status_error"></span>
                        </div>
                        <div class="divider ">
                            <div class="divider-text"><i class="bx bx-crown"></i></div>
                        </div>
                        <button type="submit" id="addCategoryBtn" class="btn btn-primary">Submit</button>
                    </form>
                    @else
                    <form id="updateCategoryForm">
                        @csrf
                        <input type="hidden" name="category_id" value="{{ $category->id }}">
                        <div class="mb-3">
                            <label class="form-label" for="category_name">Category Name</label>
                            <input type="text" class="form-control fix-word-first-letter" id="category_name" name="category_name"
                                value="{{ $category->category_name }}" />
                            <span class="text-danger" id="category_name_error"></span>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block" for="unit">Unit</label>

                            @php
                                $units = ['Nos', 'Set', 'Sq. ft', 'Sq. mtr', 'Running ft', 'Running mtr'];
                            @endphp

                            @foreach ($units as $u)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                        type="radio"
                                        name="unit"
                                        id="unit_{{ Str::slug($u, '_') }}"
                                        value="{{ $u }}"
                                        {{ (isset($category) && $category->unit === $u) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="unit_{{ Str::slug($u, '_') }}">{{ $u }}</label>
                                </div>
                            @endforeach

                            <span class="text-danger" id="unit_error"></span>
                        </div>


                        <div class="col-md">
                            <small class="form-label fw-medium d-block">Status</small>
                            @php
                            $status = $category->status ?? null;
                            @endphp

                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_active" value="1"
                                    {{ $status == 1 ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_active">Active</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_inactive"
                                    value="0" {{ $status == 0 ? 'checked' : '' }}>
                                <label class="form-check-label" for="status_inactive">Inactive</label>
                            </div>
                            <span class="text-danger" id="status_error"></span>
                        </div>
                        <div class="divider ">
                            <div class="divider-text"><i class="bx bx-crown"></i></div>
                        </div>
                        <button type="submit" id="updateCategoryBtn" class="btn btn-primary">Save changes</button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('custom-script')
<script>
$(document).ready(function() {
    $(document).ready(function() {
        // Add Category
        $('#addCategoryForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $('#addCategoryForm input, #addCategoryForm select, #addCategoryForm file').on(
                'input, change',
                function() {
                    var fieldName = $(this).attr('name');
                    clearFieldError(fieldName);
                });

            $.ajax({
                method: "POST",
                url: "{{ route('store.category') }}",
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $('#addCategoryBtn').attr('disabled', true);
                    $('#addCategoryBtn').html(
                        '<i class="fa fa-spinner fa-spin"></i> Processing...'
                    );
                },
                success: function(response) {
                    if (response.status === true) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            if (response.redirect_url) {
                                window.location.href = response
                                    .redirect_url;
                            }
                        }, 1000);
                    } else {
                        for (var field in response.errors) {
                            displayValidationError(field, response.errors[field]
                                [0]);
                        }
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    for (var field in errors) {
                        displayValidationError(field, errors[field][0]);
                    }
                },
                complete: function() {
                    $('#addCategoryBtn').attr('disabled', false);
                    $('#addCategoryBtn').html('Add');
                }
            });
        });


        // Update Category
        $('#updateCategoryForm').submit(function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $('#updateCategoryForm input, #updateCategoryForm select, #updateCategoryForm file')
                .on(
                    'input, change',
                    function() {
                        var fieldName = $(this).attr('name');
                        clearFieldError(fieldName);
                    });

            $.ajax({
                method: "POST",
                url: "{{ route('update.category') }}",
                dataType: 'json',
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
                beforeSend: function() {
                    $('#updateCategoryBtn').attr('disabled', true);
                    $('#updateCategoryBtn').html(
                        '<i class="fa fa-spinner fa-spin"></i> Processing...'
                    );
                },
                success: function(response) {
                    if (response.status === true) {
                        toastr.success(response.message);
                        setTimeout(() => {
                            if (response.redirect_url) {
                                window.location.href = response
                                    .redirect_url;
                            }
                        }, 1000);
                    } else {
                        for (var field in response.errors) {
                            displayValidationError(field, response.errors[field]
                                [0]);
                        }
                    }
                },
                error: function(response) {
                    var errors = response.responseJSON.errors;
                    for (var field in errors) {
                        displayValidationError(field, errors[field][0]);
                    }
                },
                complete: function() {
                    $('#updateCategoryBtn').attr('disabled', false);
                    $('#updateCategoryBtn').html('Save changes');
                }
            });
        });

        function displayValidationError(field, error) {
            $('#' + field + '_error').text(error);
        }

        function clearFieldError(field) {
            $('#' + field + '_error').text('');
        }
    });
});
</script>
@endpush