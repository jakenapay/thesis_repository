<?php
// app/Helpers/logger_helper.php

use App\Models\Log;

/**
 * We remove "namespace App\Helpers;" so these functions 
 * become globally available in your application.
 */

if (!class_exists('LoggerLogic')) {
    /**
     * Renamed class to avoid collision with any actual Libraries.
     * This acts as a private internal logic handler for this helper.
     */
    class LoggerLogic
    {
        public static function log(
            string $action,
            ?string $resourceType = null,
            ?int $resourceId = null,
            ?string $description = null,
            ?int $userId = null
        ): bool {
            try {
                $request = \Config\Services::request();
                $session = \Config\Services::session();

                if ($userId === null) {
                    $userId = $session->get('user_id') ?? null;
                }

                // Ensure the Log model exists and is imported correctly
                $logModel = new Log();
                
                $data = [
                    'user_id'       => $userId,
                    'action'        => $action,
                    'resource_type' => $resourceType,
                    'resource_id'   => $resourceId,
                    'description'   => $description,
                    'ip_address'    => $request->getIPAddress(),
                    'user_agent'    => $request->getUserAgent()->getAgentString(),
                ];

                return (bool) $logModel->insert($data);
            } catch (\Exception $e) {
                log_message('error', 'Logger Error: ' . $e->getMessage());
                return false;
            }
        }
    }
}

/**
 * Global helper function
 */
if (!function_exists('logAction')) {
    function logAction(
        string $action,
        ?string $resourceType = null,
        ?int $resourceId = null,
        ?string $description = null,
        ?int $userId = null
    ): bool {
        // Call the class defined above (no namespace needed now)
        return LoggerLogic::log($action, $resourceType, $resourceId, $description, $userId);
    }
}

/**
 * Get action badge color class
 */
if (!function_exists('getActionBadgeClass')) {
    function getActionBadgeClass($action)
    {
        $badgeMap = [
            'LOGIN' => 'bg-success',
            'LOGOUT' => 'bg-info',
            'LOGIN_FAILED' => 'bg-danger',
            'USER_REGISTERED' => 'bg-primary',
            'UPLOAD_DOCUMENT' => 'bg-primary',
            'CREATE_GRADUATE_THESIS' => 'bg-primary',
            'CREATE_FACULTY_RESEARCH' => 'bg-primary',
            'CREATE_DISSERTATION' => 'bg-primary',
            'UPDATE_DOCUMENT_STATUS' => 'bg-warning',
            'EDIT_GRADUATE_THESIS' => 'bg-warning',
            'EDIT_FACULTY_RESEARCH' => 'bg-warning',
            'EDIT_DISSERTATION' => 'bg-warning',
            'ENDORSE_DOCUMENT' => 'bg-success',
            'PUBLISH_DOCUMENT' => 'bg-success',
            'REQUEST_REVISION' => 'bg-warning',
            'DELETE_DOCUMENT' => 'bg-danger',
            'ADD_FEEDBACK' => 'bg-info',
            'RESUBMIT_GRADUATE_THESIS' => 'bg-info',
            'RESUBMIT_FACULTY_RESEARCH' => 'bg-info',
            'RESUBMIT_DISSERTATION' => 'bg-info',
            'UPDATE_PROFILE' => 'bg-secondary',
            'PROFILE_UPDATED' => 'bg-secondary'
        ];

        return $badgeMap[$action] ?? 'bg-secondary';
    }
}