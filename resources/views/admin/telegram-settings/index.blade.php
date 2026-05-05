@extends('layouts.admin')

@section('title', 'Telegram Settings')
@section('page_title', 'Telegram Settings')
@section('page_subtitle', 'Configure Telegram bot for automatic inventory alerts')

@section('content')

<div class="row g-4">

    <div class="col-12 col-xl-5">
        <div class="page-card h-100">
            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h5 class="mb-1">Bot Configuration</h5>
                    <p class="text-muted mb-0">
                        Save bot token once, verify bot, then connect admin chat automatically.
                    </p>
                </div>

                @if($setting->isReady())
                    <span class="badge bg-success">Ready</span>
                @elseif($setting->is_active)
                    <span class="badge bg-warning text-dark">Bot Verified</span>
                @else
                    <span class="badge bg-secondary">Not Ready</span>
                @endif
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fa-solid fa-check-circle me-1"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    <i class="fa-solid fa-triangle-exclamation me-1"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-4">
                <label class="form-label">Telegram Bot Token</label>

                @if(!$setting->bot_token)
                    <form method="POST" action="{{ route('admin.telegram-settings.update') }}">
                        @csrf

                        <div class="input-group">
                            <input
                                type="password"
                                name="bot_token"
                                class="form-control"
                                placeholder="Example: 123456789:ABCDEF..."
                                required
                            >

                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-save me-1"></i>
                                Save Token
                            </button>
                        </div>

                        <div class="form-text">
                            Token will be stored encrypted in database. This token is global for the whole application.
                        </div>
                    </form>
                @else
                    <div class="d-flex flex-column flex-md-row gap-2 align-items-md-center">
                        <div class="form-control bg-light d-flex align-items-center">
                            <i class="fa-solid fa-lock me-2 text-success"></i>
                            Bot token already saved
                        </div>

                        <form method="POST"
                              action="{{ route('admin.telegram-settings.reset-token') }}"
                              onsubmit="return confirm('Are you sure? Resetting token will disconnect current Telegram bot.')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fa-solid fa-rotate-left me-1"></i>
                                Reset Token
                            </button>
                        </form>
                    </div>

                    <div class="form-text">
                        Same bot token will be used for the whole application.
                    </div>
                @endif
            </div>

            <div class="telegram-steps">

                <div class="telegram-step {{ $setting->bot_token ? 'done' : '' }}">
                    <div class="step-icon">
                        <i class="fa-solid fa-key"></i>
                    </div>

                    <div class="flex-grow-1">
                        <div class="fw-semibold">Step 1: Save Bot Token</div>
                        <div class="small text-muted">
                            Bot token is saved once and used globally for all alerts.
                        </div>
                    </div>
                </div>

                <div class="telegram-step {{ $setting->is_active ? 'done' : '' }}">
                    <div class="step-icon">
                        <i class="fa-solid fa-robot"></i>
                    </div>

                    <div class="flex-grow-1">
                        <div class="fw-semibold">Step 2: Verify Bot</div>
                        <div class="small text-muted">
                            Check token and detect bot username.
                        </div>

                        <form method="POST" action="{{ route('admin.telegram-settings.verify') }}" class="mt-2">
                            @csrf

                            <button type="submit"
                                    class="btn btn-sm btn-outline-primary"
                                    {{ !$setting->bot_token ? 'disabled' : '' }}>
                                <i class="fa-solid fa-circle-check me-1"></i>
                                Verify Bot
                            </button>
                        </form>
                    </div>
                </div>

                <div class="telegram-step {{ $setting->admin_chat_id ? 'done' : '' }}">
                    <div class="step-icon">
                        <i class="fa-brands fa-telegram"></i>
                    </div>

                    <div class="flex-grow-1">
                        <div class="fw-semibold">Step 3: Start Bot</div>
                        <div class="small text-muted">
                            Telegram rule: admin must click Start once. No chat ID copy needed.
                        </div>

                        @if($setting->botStartUrl())
                            <a
                                href="{{ $setting->botStartUrl() }}"
                                target="_blank"
                                class="btn btn-sm btn-dark mt-2"
                            >
                                <i class="fa-brands fa-telegram me-1"></i>
                                Open Telegram & Start Bot
                            </a>
                        @else
                            <button class="btn btn-sm btn-dark mt-2" disabled>
                                <i class="fa-brands fa-telegram me-1"></i>
                                Open Telegram & Start Bot
                            </button>
                        @endif
                    </div>
                </div>

                <div class="telegram-step {{ $setting->admin_chat_id ? 'done' : '' }}">
                    <div class="step-icon">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>

                    <div class="flex-grow-1">
                        <div class="fw-semibold">Step 4: Auto Detect Admin Chat</div>
                        <div class="small text-muted">
                            After clicking Start, system will auto detect chat ID from Telegram.
                        </div>

                        <form method="POST" action="{{ route('admin.telegram-settings.sync-chat') }}" class="mt-2">
                            @csrf

                            <button type="submit"
                                    class="btn btn-sm btn-outline-dark"
                                    {{ !$setting->is_active ? 'disabled' : '' }}>
                                <i class="fa-solid fa-rotate me-1"></i>
                                Auto Detect Chat ID
                            </button>
                        </form>
                    </div>
                </div>

                <div class="telegram-step {{ $setting->isReady() ? 'done' : '' }}">
                    <div class="step-icon">
                        <i class="fa-solid fa-vial-circle-check"></i>
                    </div>

                    <div class="flex-grow-1">
                        <div class="fw-semibold">Step 5: Send Test Message</div>
                        <div class="small text-muted">
                            Confirm Telegram alert is working.
                        </div>

                        <form method="POST" action="{{ route('admin.telegram-settings.test') }}" class="mt-2">
                            @csrf

                            <button type="submit"
                                    class="btn btn-sm btn-success"
                                    {{ !$setting->isReady() ? 'disabled' : '' }}>
                                <i class="fa-solid fa-paper-plane me-1"></i>
                                Send Test Message
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-12 col-xl-7">
        <div class="page-card mb-4">
            <h5 class="mb-3">Current Status</h5>

            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0">
                    <tbody>
                        <tr>
                            <th style="width: 220px;">Bot Token</th>
                            <td>
                                @if($setting->bot_token)
                                    <span class="badge bg-success">Saved</span>
                                @else
                                    <span class="badge bg-secondary">Not Saved</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Bot Username</th>
                            <td>
                                @if($setting->bot_username)
                                    <span class="fw-semibold">{{ '@' . $setting->bot_username }}</span>
                                @else
                                    <span class="text-muted">Not detected</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Admin Chat ID</th>
                            <td>
                                @if($setting->admin_chat_id)
                                    <span class="badge bg-success">Detected</span>
                                    <span class="text-muted ms-2">{{ $setting->admin_chat_id }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">Not Detected</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Active</th>
                            <td>
                                @if($setting->is_active)
                                    <span class="badge bg-success">Yes</span>
                                @else
                                    <span class="badge bg-secondary">No</span>
                                @endif
                            </td>
                        </tr>

                        <tr>
                            <th>Last Verified</th>
                            <td>
                                {{ $setting->last_verified_at ? $setting->last_verified_at->format('d M Y, h:i A') : '-' }}
                            </td>
                        </tr>

                        <tr>
                            <th>Last Chat Sync</th>
                            <td>
                                {{ $setting->last_chat_synced_at ? $setting->last_chat_synced_at->format('d M Y, h:i A') : '-' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="page-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="mb-1">Telegram Logs</h5>
                    <p class="text-muted mb-0">Last 20 Telegram message attempts.</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Time</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Message</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td class="small text-muted">
                                    {{ $log->created_at->format('d M, h:i A') }}
                                </td>

                                <td>
                                    <span class="badge bg-light text-dark">
                                        {{ ucfirst($log->type) }}
                                    </span>
                                </td>

                                <td>
                                    @if($log->status === 'success')
                                        <span class="badge bg-success">Success</span>
                                    @elseif($log->status === 'failed')
                                        <span class="badge bg-danger">Failed</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>

                                <td class="small">
                                    {{ \Illuminate\Support\Str::limit(strip_tags($log->message), 80) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    <i class="fa-solid fa-inbox fa-2x mb-2"></i>
                                    <div>No Telegram log found.</div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

</div>

@endsection

@push('styles')
<style>
    .telegram-steps {
        display: flex;
        flex-direction: column;
        gap: 14px;
    }

    .telegram-step {
        display: flex;
        gap: 14px;
        padding: 14px;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        background: #ffffff;
    }

    .telegram-step.done {
        border-color: #bbf7d0;
        background: #f0fdf4;
    }

    .step-icon {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #111827;
        flex-shrink: 0;
    }

    .telegram-step.done .step-icon {
        background: #16a34a;
        color: #ffffff;
    }
</style>
@endpush