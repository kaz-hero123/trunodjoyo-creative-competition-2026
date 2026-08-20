<?php
namespace App\Http\Controllers;
use App\Models\Resource;
use App\Models\ResourceMatch;
use Illuminate\Http\Request;
class ResourceController extends Controller {
    public function index() {
        $resources = Resource::active()
            ->where(function($q) {
                $q->whereNull('deadline')->orWhere('deadline', '>=', now()->toDateString());
            })
            ->get();
        return view('resources.index', compact('resources'));
    }
    
    public function recordClick(ResourceMatch $match, Request $request) {
        if ($match->assessment->user_id !== $request->user()->id) {
            abort(403);
        }
        $match->update(['is_clicked' => true]);
        return redirect($match->resource->url ?? '#');
    }
}
