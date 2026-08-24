<?php

namespace App\Http\Controllers\Workspace;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $notes = $request->user()->notes()->latest()->get();
        return view('workspace.notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('workspace.notes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        $note = $request->user()->notes()->create($request->validated());
        return redirect()->route('workspace.notes.show', $note)->with('success', 'Catatan berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $note = Note::findOrFail($id);
        if ($note->user_id !== $request->user()->id) abort(403);
        return view('workspace.notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        $note = Note::findOrFail($id);
        if ($note->user_id !== $request->user()->id) abort(403);
        return view('workspace.notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, string $id)
    {
        $note = Note::findOrFail($id);
        if ($note->user_id !== $request->user()->id) abort(403);
        
        $note->update($request->validated());
        return redirect()->route('workspace.notes.show', $note)->with('success', 'Catatan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $note = Note::findOrFail($id);
        if ($note->user_id !== $request->user()->id) abort(403);
        
        $note->delete();
        return redirect()->route('workspace.notes.index')->with('success', 'Catatan berhasil dihapus.');
    }
}
