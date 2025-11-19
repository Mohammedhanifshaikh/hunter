@extends('layouts.master')
@section('title', $mode === 'add' ? 'Add Subscription' : 'Edit Subscription')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ $mode == 'add' ? 'Add Subscription' : 'Edit Subscription' }}</h5>
                    </div>
                    <div class="card-body">
                        @if ($mode == 'add')
                            <form id="addSubscriptionForm">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label" for="name">Subscription Name</label>
                                    <input type="text" class="form-control fix-word-first-letter" id="name"
                                        name="name" placeholder="Subscription Name" />
                                    <span class="text-danger" id="name_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" placeholder="Description" id="description" name="description" rows="3"></textarea>
                                    <span class="text-danger" id="description_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="agents" class="form-label">Agents</label>
                                    <input type="number" class="form-control" id="agents" name="agents"
                                        placeholder="Agents">
                                    <span class="text-danger" id="agents_error"></span>
                                </div>



                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration</label>
                                    <input type="number" class="form-control" id="duration" name="duration"
                                        placeholder="Duration">
                                    <span class="text-danger" id="duration_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" class="form-control" id="price" name="price"
                                        placeholder="Price">
                                    <span class="text-danger" id="price_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="features" class="form-label">Features</label>
                                    <textarea class="form-control" placeholder="Features" id="features" name="features" rows="3"></textarea>
                                    <span class="text-danger" id="features_error"></span>
                                </div>

                                <div class="col-md">
                                    <small class="form-label fw-medium d-block">Status</small>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" checked name="status" id="active"
                                            value="1" />
                                        <label class="form-check-label" for="active">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="inactive"
                                            value="0" />
                                        <label class="form-check-label" for="inactive">Inactive</label>
                                    </div>
                                    <span class="text-danger" id="status_error"></span>
                                </div>
                                <div class="divider ">
                                    <div class="divider-text"><i class="bx bx-crown"></i></div>
                                </div>
                                <button type="submit" class="btn btn-primary" id="addSubscriptionBtn">Submit</button>
                            </form>
                        @else
                            <form id="updateSubscriptionForm">
                                @csrf
                                <input type="hidden" name="subscription_id" value="{{ $subscription->id }}">

                                <div class="mb-3">
                                    <label class="form-label" for="name">Subscription Name</label>
                                    <input type="text" class="form-control fix-word-first-letter" id="name"
                                        name="name" value="{{ $subscription->name }}" />
                                    <span class="text-danger" id="name_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" placeholder="Description" id="description" name="description" rows="3">{{ $subscription->description }}</textarea>
                                    <span class="text-danger" id="description_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="agents" class="form-label">Agents</label>
                                    <input type="number" class="form-control" id="agents" name="agents"
                                        value="{{ $subscription->agents }}" placeholder="Agents">
                                    <span class="text-danger" id="agents_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="duration" class="form-label">Duration</label>
                                    <input type="number" class="form-control" id="duration" name="duration"
                                        value="{{ $subscription->duration }}" placeholder="Duration">
                                    <span class="text-danger" id="duration_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" class="form-control" id="price" name="price"
                                        value="{{ $subscription->price }}" placeholder="Price">
                                    <span class="text-danger" id="price_error"></span>
                                </div>

                                <div class="mb-3">
                                    <label for="features" class="form-label">Features</label>
                                    <textarea class="form-control" placeholder="Features" id="features" name="features" rows="3">{{ $subscription->features }}</textarea>
                                    <span class="text-danger" id="features_error"></span>
                                </div>


                                <div class="col-md">
                                    <small class="form-label fw-medium d-block">Status</small>
                                    @php
                                        $status = $subscription->status ?? null;
                                    @endphp

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status" id="status_active"
                                            value="1" {{ $status == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_active">Active</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status"
                                            id="status_inactive" value="0" {{ $status == 0 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="status_inactive">Inactive</label>
                                    </div>
                                </div>
                                <div class="divider ">
                                    <div class="divider-text"><i class="bx bx-crown"></i></div>
                                </div>
                                <button type="submit" class="btn btn-primary" id="updateSubscriptionBtn">Save
                                    changes</button>
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
        // Description CheckEditor
        CKEDITOR.ClassicEditor.create(document.getElementById("features"), {
            toolbar: {
                items: [
                    'bold', 'italic', 'underline', 'subscript', 'superscript', '|',
                    'bulletedList', 'numberedList', '|',
                    'sourceEditing', 'removeFormat', 'heading',
                ],
                shouldNotGroupWhenFull: true
            },
            list: {
                properties: {
                    styles: false,
                    startIndex: true,
                    reversed: true
                }
            },
            heading: {
                options: [{
                        model: 'paragraph',
                        title: 'Paragraph',
                        class: 'ck-heading_paragraph'
                    },
                    {
                        model: 'heading1',
                        view: 'h1',
                        title: 'Heading 1',
                        class: 'ck-heading_heading1'
                    },
                    {
                        model: 'heading2',
                        view: 'h2',
                        title: 'Heading 2',
                        class: 'ck-heading_heading2'
                    },
                    {
                        model: 'heading3',
                        view: 'h3',
                        title: 'Heading 3',
                        class: 'ck-heading_heading3'
                    },
                    {
                        model: 'heading4',
                        view: 'h4',
                        title: 'Heading 4',
                        class: 'ck-heading_heading4'
                    },
                    {
                        model: 'heading5',
                        view: 'h5',
                        title: 'Heading 5',
                        class: 'ck-heading_heading5'
                    },
                    {
                        model: 'heading6',
                        view: 'h6',
                        title: 'Heading 6',
                        class: 'ck-heading_heading6'
                    }
                ]
            },
            placeholder: 'Description',
            fontFamily: {
                options: [
                    'default',
                    'Arial, Helvetica, sans-serif',
                    'Courier New, Courier, monospace',
                    'Georgia, serif',
                    'Lucida Sans Unicode, Lucida Grande, sans-serif',
                    'Tahoma, Geneva, sans-serif',
                    'Times New Roman, Times, serif',
                    'Trebuchet MS, Helvetica, sans-serif',
                    'Verdana, Geneva, sans-serif'
                ],
                supportAllValues: true
            },
            fontSize: {
                options: [10, 12, 14, 'default', 18, 20, 22],
                supportAllValues: true
            },
            htmlSupport: {
                allow: [{
                    name: /.*/,
                    attributes: true,
                    classes: true,
                    styles: false
                }]
            },
            htmlEmbed: {
                showPreviews: true
            },
            link: {
                decorators: {
                    addTargetToExternalLinks: true,
                    defaultProtocol: 'https://',
                    toggleDownloadable: {
                        mode: 'manual',
                        label: 'Downloadable',
                        attributes: {
                            download: 'file'
                        }
                    }
                }
            },
            mention: {
                feeds: [{
                    marker: '@',
                    feed: [
                        '@apple', '@bears', '@brownie', '@cake', '@cake', '@candy', '@canes',
                        '@chocolate', '@cookie', '@cotton', '@cream',
                        '@cupcake', '@danish', '@donut', '@dragée', '@fruitcake', '@gingerbread',
                        '@gummi', '@ice', '@jelly-o',
                        '@liquorice', '@macaroon', '@marzipan', '@oat', '@pie', '@plum', '@pudding',
                        '@sesame', '@snaps', '@soufflé',
                        '@sugar', '@sweet', '@topping', '@wafer'
                    ],
                    minimumCharacters: 1
                }]
            },
            removePlugins: [
                // 'ExportPdf',
                // 'ExportWord',
                'CKBox',
                'CKFinder',
                'EasyImage',
                'RealTimeCollaborativeComments',
                'RealTimeCollaborativeTrackChanges',
                'RealTimeCollaborativeRevisionHistory',
                'PresenceList',
                'Comments',
                'TrackChanges',
                'TrackChangesData',
                'RevisionHistory',
                'Pagination',
                'WProofreader',
                'MathType'
            ]
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js"
        integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/css/selectize.bootstrap3.min.css"
        integrity="sha256-ze/OEYGcFbPRmvCnrSeKbRTtjG4vGLHXgOqsyLFTRjg=" crossorigin="anonymous" />


    </script>
    <script>
        $(document).ready(function() {
            $(document).ready(function() {
                // Add Hospital
                $('#addSubscriptionForm').submit(function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);

                    $('#addSubscriptionForm input, #addSubscriptionForm select, #addSubscriptionForm file')
                        .on(
                            'input, change',
                            function() {
                                var fieldName = $(this).attr('name');
                                clearFieldError(fieldName);
                            });

                    $.ajax({
                        method: "POST",
                        url: "{{ route('store.subscription') }}",
                        dataType: 'json',
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function() {
                            $('#addSubscriptionBtn').attr('disabled', true);
                            $('#addSubscriptionBtn').html(
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
                            $('#addSubscriptionBtn').attr('disabled', false);
                            $('#addSubscriptionBtn').html('Add');
                        }
                    });
                });

                // Update Hospital
                $('#updateSubscriptionForm').submit(function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);

                    $('#updateSubscriptionForm input, #updateSubscriptionForm select, #updateSubscriptionForm file')
                        .on(
                            'input, change',
                            function() {
                                var fieldName = $(this).attr('name');
                                clearFieldError(fieldName);
                            });

                    $.ajax({
                        method: "POST",
                        url: "{{ route('update.subscription') }}",
                        dataType: 'json',
                        data: formData,
                        processData: false,
                        contentType: false,
                        cache: false,
                        beforeSend: function() {
                            $('#updateSubscriptionBtn').attr('disabled', true);
                            $('#updateSubscriptionBtn').html(
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
                            $('#updateSubscriptionBtn').attr('disabled', false);
                            $('#updateSubscriptionBtn').html('Save changes');
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
