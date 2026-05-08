<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoginUser;
use App\Models\OAuthAccessToken;
use App\Models\Role;
use App\Models\User;
use App\Services\OAuth2Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'login'    => 'required|string',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                Log::build(['driver' => 'single', 'path' => storage_path('logs/auth.log')])
                    ->error('Validator errors: ', ['requests' => $request->all(), 'trace' => $validator->errors()]);
                return $this->error('Unauthorized', 401);
            }

            $user = User::with('roles', 'oauth_access_tokens')->where('login', $request->login)->first();

            if (!$user) {
                Log::build(['driver' => 'single', 'path' => storage_path('logs/auth.log')])
                    ->error('User not found: ', ['requests' => $request->all()]);
                return $this->error('Unauthorized', 401);
            }

            if (!$user->status || !Hash::check($request->password, $user->password)) {
                Log::build(['driver' => 'single', 'path' => storage_path('logs/auth.log')])
                    ->error('User check status and password: ', ['requests' => $request->all(), 'user_status' => $user->status]);
                return $this->error('Unauthorized', 401);
            }

            $token = (new OAuth2Service)->token($request->all());

            if (!isset($token['data'])) {
                Log::build(['driver' => 'single', 'path' => storage_path('logs/auth.log')])
                    ->error('Token error: ', ['requests' => $request->all(), 'trace' => $token]);
                return $this->error('Unauthorized', 401);
            }

            $token = $token['data'];
            OAuthAccessToken::where('user_id', $user->id)->update(['name' => $user->login]);

            $login = LoginUser::create([
                'user_id'              => $user->id,
                'access_token'         => $token['access_token'],
                'refresh_token'        => $token['refresh_token'],
                'token_type'           => $token['token_type'],
                'access_token_expires' => $token['expires_in'],
                'ip_address'           => $request->header('x-forwarded-for') ?? $request->ip(),
                'user_agent'           => $request->userAgent() ?? null,
                'expires_at'           => Carbon::now()->addMonth(),
            ]);

            $token['login_id']   = $login->id;
            $token['created_at'] = $login->created_at->toDateTimeString();

            return $this->success($this->getAuthData($user, $token), 'Login successful');
        } catch (\Exception $e) {
            Log::build(['driver' => 'single', 'path' => storage_path('logs/auth.log')])
                ->error($e->getMessage(), ['requests' => $request->all(), 'trace' => $e->getTrace()]);
            return $this->error('Unauthorized', 401);
        }
    }

    private function getAuthData(User $user, $token): array
    {
        $role_id = $user->roles()->pluck('id')->first();

        $user_info = [
            'first_name' => $user->first_name,
            'last_name'  => $user->last_name,
            'patronymic' => $user->patronymic,
        ];

        $subsectionIds = $role_id
            ? DB::table('subsection_role')->where('role_id', $role_id)->pluck('subsection_id')
            : collect();

        $sections = $subsectionIds->isNotEmpty()
            ? DB::table('sections')
                ->join('subsections', 'sections.id', '=', 'subsections.section_id')
                ->whereIn('subsections.id', $subsectionIds)
                ->select(
                    'sections.title as section_title',
                    'sections.icon as section_icon',
                    'subsections.title as subsection_title',
                    'subsections.component_id as subsection_component_id'
                )
                ->orderBy('sections.title')
                ->orderBy('subsections.title')
                ->get()
                ->groupBy('section_title')
                ->map(function ($items) {
                    return [
                        'title'       => $items->first()->section_title,
                        'icon'        => $items->first()->section_icon,
                        'subsections' => $items->sortBy('subsection_title')->map(fn($item) => [
                            'title'        => $item->subsection_title,
                            'component_id' => $item->subsection_component_id,
                        ])->values(),
                    ];
                })
                ->sortBy('title')
                ->values()
            : collect();

        return [
            'id'                    => $token['login_id'],
            'user_id'               => $user->id,
            'token_type'            => $token['token_type'],
            'access_token'          => $token['access_token'],
            'access_token_expires'  => $token['expires_in'],
            'access_token_expDate'  => Carbon::now()->addSeconds($token['expires_in'])->toDateTimeString(),
            'refresh_token'         => $token['refresh_token'],
            'role_id'               => $role_id,
            'division_id'           => $user?->employee?->division_id,
            'user_info'             => $user_info,
            'created_at'            => $token['created_at'],
            'role_ru'               => $user?->roles()->first()?->display_name,
            'section'               => $sections,
            'user_type'             => $user?->employee?->user_type ?? 0,
        ];
    }
}
