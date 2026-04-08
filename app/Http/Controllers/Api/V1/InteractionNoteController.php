<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\InteractionNote\StoreInteractionNoteRequest;
use App\Http\Requests\Api\V1\InteractionNote\UpdateInteractionNoteRequest;
use App\Http\Resources\Api\V1\InteractionNoteResource;
use App\Models\InteractionNote;

class InteractionNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $interactionNotes = InteractionNote::latest()->paginate(10);

        return $this->paginatedResponse($interactionNotes, __('lang.Interaction Notes retrieved successfully'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInteractionNoteRequest $request)
    {
        $interactionNote = InteractionNote::create($request->validated());

        return $this->successResponse(new InteractionNoteResource($interactionNote), __('lang.Interaction Note created successfully'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(InteractionNote $interactionNote)
    {
        return $this->successResponse(new InteractionNoteResource($interactionNote), __('lang.Interaction Note retrieved successfully'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateInteractionNoteRequest $request, InteractionNote $interactionNote)
    {
        $interactionNote->update($request->validated());

        return $this->successResponse(new InteractionNoteResource($interactionNote), __('lang.Interaction Note updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InteractionNote $interactionNote)
    {
        $interactionNote->delete();

        return $this->successResponse(null, __('lang.Interaction Note deleted successfully'));
    }
}
