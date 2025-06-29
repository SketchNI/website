<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\Backend\AuditLogResource;
use Inertia\Response;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    public function __invoke(): Response
    {
        $breadcrumbs = [
            [
                'route' => route('backend.misc.audit-log.index'),
                'name' => 'Audit Logs',
                'active' => request()->routeIs('backend.misc.audit-log.index'),
            ],
        ];

        $logs = Activity::with(['causer', 'subject'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return inertia('Backend/AuditLog/Index', [
            'logs' => AuditLogResource::collection($logs),
            'breadcrumbs' => $breadcrumbs,
        ]);
    }
}
