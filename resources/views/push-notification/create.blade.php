@extends('layout')
@section('title', trans('lang.individual_notifications'))

@section('styles')
<style>
    .notification-page {
        padding: 2rem;
    }

    .page-heading {
        margin-bottom: 2rem;
    }

    .page-heading h1 {
        font-size: 1.5rem;
        font-weight: 600;
        color: #2d3748;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }

    .user-table {
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .table-header-actions {
        display: flex;
        justify-content: space-between;
        padding: 1rem;
        background: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
    }

    .search-filter {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .table-container {
        overflow-x: auto;
    }

    .user-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .user-table th,
    .user-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid #e2e8f0;
        font-size: 0.875rem;
    }

    .user-table th {
        background: #f8fafc;
        font-weight: 600;
        color: #4a5568;
    }

    .user-table tr:hover {
        background-color: #f8fafc;
    }

    .notification-form {
        margin-top: 2rem;
        background: #fff;
        border-radius: 0.75rem;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .selected-count {
        background: #4299e1;
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.875rem;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .table-header-actions {
            flex-direction: column;
            gap: 1rem;
        }
    }

</style>
@endsection

@section('content')
<div class="notification-page">
    <div class="page-heading">
        <h1>
            <i class="fas fa-user-clock"></i>
            @lang('lang.send_individual_notifications')
        </h1>
        <p>@lang('lang.select_users_and_compose_message')</p>
    </div>

    @include('partials._alerts')

    <div class="user-table">
        <div class="table-header-actions">
            <div class="search-filter">
                <input type="text" placeholder="@lang('lang.search_users')" class="form-control" style="max-width: 300px;">
                <select class="form-control" style="max-width: 200px;">
                    <option>@lang('lang.all_account_types')</option>
                    @foreach($userAccountTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="selected-count">
                <span id="selectedCount">0</span> @lang('lang.selected')
            </div>
        </div>

        <div class="table-container">
            <form id="notificationForm" action="{{ route('notifications.send.individual') }}" method="POST">
                @csrf
                <table class="table">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="selectAll"></th>
                            <th>@lang('lang.name')</th>
                            <th>@lang('lang.email')</th>
                            <th>@lang('lang.phone')</th>
                            <th>@lang('lang.account_type')</th>
                            <th>@lang('lang.status')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td><input type="checkbox" name="users[]" value="{{ $user->id }}"></td>
                            <td>{{ $user->full_name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td><span class="badge bg-primary">{{ $user->accountType->name }}</span></td>
                            <td><span class="badge {{ $user->status->color }}">{{ $user->status->name }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">@lang('lang.no_users_found')</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
        </div>
    </div>

    <div class="notification-form">
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">@lang('lang.ar_title') *</label>
                <input type="text" name="ar_title" class="form-control" required dir="rtl">
            </div>

            <div class="form-group">
                <label class="form-label">@lang('lang.en_title') *</label>
                <input type="text" name="en_title" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">@lang('lang.ar_message') *</label>
                <textarea name="ar_body" class="form-control" rows="4" required dir="rtl"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">@lang('lang.en_message') *</label>
                <textarea name="en_body" class="form-control" rows="4" required></textarea>
            </div>
        </div>

        <div class="form-actions">
            <button type="button" onclick="window.history.back()" class="btn btn-default">
                <i class="fas fa-arrow-left"></i>
                @lang('lang.cancel')
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-paper-plane"></i>
                @lang('lang.send_notifications')
            </button>
        </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select/Deselect all functionality
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('input[name="users[]"]');
        const selectedCount = document.getElementById('selectedCount');

        function updateCount() {
            const selected = document.querySelectorAll('input[name="users[]"]:checked').length;
            selectedCount.textContent = selected;
        }

        selectAll.addEventListener('change', (e) => {
            checkboxes.forEach(checkbox => {
                checkbox.checked = e.target.checked;
            });
            updateCount();
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateCount);
        });

        // Form validation
        const form = document.getElementById('notificationForm');

        form.addEventListener('submit', function(e) {
            const selectedUsers = document.querySelectorAll('input[name="users[]"]:checked').length;
            if (selectedUsers === 0) {
                e.preventDefault();
                alert('@lang('
                    lang.select_at_least_one_user ')');
                return false;
            }

            // Add loading state
            const submitButton = form.querySelector('button[type="submit"]');
            submitButton.innerHTML = `<i class="fas fa-spinner fa-spin"></i> @lang('lang.sending')...`;
            submitButton.disabled = true;
        });

        // Initialize DataTable
        $('#notificationForm table').DataTable({
            dom: '<"top"f>rt<"bottom"lip><"clear">'
            , language: {
                search: '@lang('
                lang.search ')'
                , info: '@lang('
                lang.showing_entries ')'
                , lengthMenu: '@lang('
                lang.show_entries ')'
                , paginate: {
                    previous: '@lang('
                    lang.previous ')'
                    , next: '@lang('
                    lang.next ')'
                }
            }
            , columnDefs: [{
                orderable: false
                , targets: 0
            }]
        });
    });

</script>
@endsection
