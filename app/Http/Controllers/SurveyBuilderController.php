<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use App\Models\SurveySection;
use App\Models\Ward;
use App\Services\SurveyBuilderService;
use Illuminate\Http\Request;

class SurveyBuilderController extends Controller
{
    public function __construct(private readonly SurveyBuilderService $surveyService)
    {}


    public function index(Request $request)
    {
        $this->authorize('manage', SurveySection::class);

        $authUser = $request->user();
        $wards    = $this->surveyService->getAccessibleWards($authUser);

        $validated      = $request->validate(['ward_id' => 'nullable|integer|exists:wards,id']);
        $selectedWardId = $this->surveyService->resolveWardId($authUser, $validated['ward_id'] ?? null);

        $sections = $selectedWardId
            ? $this->surveyService->getSectionsForWard($selectedWardId)
            : collect();

        return view('surveybuilder.managesections', compact('sections', 'wards', 'selectedWardId'));
    }

    public function create()
    {
        $this->authorize('manage', SurveySection::class);

        $wards = $this->surveyService->getAccessibleWards(auth()->user());

        return view('surveybuilder.createsurveyquestion', compact('wards'));
    }

    public function store(StoreSurveyRequest $request)
    {
        $authUser = $request->user();

        // Ward Admins may never submit "all" or a foreign ward.
        if (! $authUser->isSuperAdmin()) {
            $wardId = $request->input('ward_id');
            if ($wardId === 'all' || $wardId != $authUser->ward_id) {
                return redirect()->back()
                    ->with('error', 'Unauthorized ward selection.')
                    ->withInput();
            }
        }

        $wardIds = $request->input('ward_id') === 'all'
            ? Ward::pluck('id')->toArray()
            : [$request->input('ward_id')];

        try {
            $this->surveyService->createSurvey($wardIds, [
                'sections'  => $request->input('sections'),
                'questions' => $request->input('questions'),
            ]);

            $message = $request->input('ward_id') === 'all'
                ? 'Survey created successfully for all wards'
                : 'Survey created successfully';

            return redirect()->back()->with('success', $message);

        } catch (\Exception) {
            return redirect()->back()
                ->with('error', 'Failed to create survey. Please try again.')
                ->withInput();
        }
    }

    
    public function edit(int $id)
    {
        $section = $this->surveyService->getSectionForEdit($id);
        $this->authorize('modifySection', $section);
        $ward_id = $section->ward_id;
        $wards = $this->surveyService->getAccessibleWards(auth()->user());
        ['formattedSections' => $formattedSections, 'formattedQuestions' => $formattedQuestions] = $this->surveyService->formatSectionForBuilder($section);
        return view('surveybuilder.editsurveyquestion', compact('wards', 'ward_id', 'formattedSections', 'formattedQuestions', 'section'));
    }

    public function update(UpdateSurveyRequest $request, int $id)
    {
        $section = SurveySection::findOrFail($id);

        $this->authorize('modifySection', $section);

        try {
            $this->surveyService->updateSurvey($section, [
                'sections'  => $request->input('sections'),
                'questions' => $request->input('questions'),
            ], (int) $request->input('ward_id'));

            return redirect()
                ->route('surveyform.index', ['ward_id' => $request->input('ward_id')])
                ->with('success', 'Section updated successfully');

        } catch (\Exception) {
            return redirect()->back()
                ->with('error', 'Failed to update section. Please try again.')
                ->withInput();
        }
    }

    public function destroy(int $id)
    {
        $section = SurveySection::findOrFail($id);

        $this->authorize('modifySection', $section);

        $wardId = $section->ward_id;

        try {
            $this->surveyService->deleteSection($section);

            return redirect()
                ->route('surveyform.index', ['ward_id' => $wardId])
                ->with('success', 'Section and its questions deleted successfully');

        } catch (\Exception) {
            return redirect()->back()
                ->with('error', 'Failed to delete section. Please try again.');
        }
    }


    public function reorder(Request $request)
    {
        $this->authorize('manage', SurveySection::class);

        $validated = $request->validate([
            'order'               => 'required|array',
            'order.*.id'          => 'required|integer|exists:survey_sections,id',
            'order.*.order_index' => 'required|integer|min:1',
        ]);

        $this->surveyService->reorderSections($request->user(), $validated['order']);

        return response()->json(['success' => true]);
    }

}