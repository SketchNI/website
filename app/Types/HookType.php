<?php

namespace App\Types;

enum HookType: string
{
    case HOOK_SIDEBAR = 'sidebar';
    case HOOK_FOOTER = 'footer';
    case HOOK_PRE_CONTENT = 'pre-content';
    case HOOK_POST_CONTENT = 'post-content';
    case HOOK_ADMIN_SIDEBAR = 'admin::sidebar';
    case HOOK_ADMIN_FOOTER = 'admin::footer';
    case HOOK_ADMIN_PRE_CONTENT = 'admin::pre-content';
    case HOOK_ADMIN_POST_CONTENT = 'admin::post-content';
}
