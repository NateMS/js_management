<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class DeploymentController extends Controller
{
    function deploy(Request $request)
    {
        Artisan::call('config:clear');
        Artisan::call('config:cache');
    
        if (request('key') !== config('app.deploykey')) {
            abort(403, 'Unauthorized');
        }
    
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:clear');
    
        $firstTimeMigration = !Schema::hasTable('users');
    
        try {
            Artisan::call('migrate --force');
        } catch (Exception $e) {
            return response()->json([
                'title:' => 'error with migration',
                'message:' => $e
            ], 500);
    
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');
        }
    
        if ($firstTimeMigration) {
            try {
                Artisan::call('db:seed --force');
            } catch (Exception $e) {
                return response()->json([
                    'title:' => 'error with db:seed',
                    'message:' => $e
                ], 500);
    
                Artisan::call('config:cache');
                Artisan::call('route:cache');
                Artisan::call('view:cache');
            }
        }
    
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');
    
        return response()->json(['message' => 'Deployment complete!'], 200);
    }
}
