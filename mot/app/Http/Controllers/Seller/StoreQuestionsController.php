<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\StoreQuestion;
use App\Models\StoreQuestionReply;
use App\Service\StoreQuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class StoreQuestionsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:seller');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->guard('seller')->user();
        $records = StoreQuestion::whereIsArchive(false)->whereStoreId($user->store_id)->latest()->get();

        return view('seller.store-questions.index', [
            'title' => __('Questions'),
            'records' => $records
        ]);
    }

    /**
     * Show the detail page for the specified resource.
     *
     * @param StoreQuestion $question
     * @return \Illuminate\Http\Response
     */
    public function detail(StoreQuestion $question)
    {
        // authorize user
        $this->authorize('canView', $question);

        // update question status
        $question->status = StoreQuestion::VIEWED;
        $question->save();

        $replies = StoreQuestionReply::where('store_question_id' , $question->id)->get();

        return view('seller.store-questions.detail', [
            'title' => __('Detail'),
            'section_title' => __('Questions'),
            'row' => $question,
            'replies' => $replies,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param StoreQuestion $question
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, StoreQuestion $question)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
        ]);

        $contactInquiryService = new StoreQuestionService();
        $response = $contactInquiryService->sendReply($request->toArray(), $question);

        return redirect()->route('seller.store.questions')->with('success', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param StoreQuestion $question
     * @return \Illuminate\Http\Response
     */
    public function delete(StoreQuestion $question)
    {
        // authorize user
        $this->authorize('canDelete', $question);

        $question->delete();
        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * Archive the specified resource from storage.
     *
     * @param StoreQuestion $question
     * @return \Illuminate\Http\Response
     */
    public function archive(StoreQuestion $question)
    {
        // authorize user
        $this->authorize('canArchive', $question);

        $question->is_archive = true;
        $question->save();

        return back()->with('success', __('Record archived successfully.'));
    }

    /**
     * apply bulk actoins
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function bulkActions(Request $request)
    {
        $request->validate([
            'bulk_action' => 'required',
            'questions' => 'required|array|min:1'
        ], [
            'questions.required' => __('You must select at least 1 question.')
        ]);

        if ($request->bulk_action === 'viewed') {
            $message = $this->markAsViewedBulkQuestions($request->questions);
            return back()->with('success', $message);
        }

        if ($request->bulk_action === 'archive') {
            $message = $this->archiveBulkQuestions($request->questions);
            return back()->with('success', $message);
        }

        if ($request->bulk_action === 'delete') {
            $message = $this->deleteBulkQuestions($request->questions);
            return back()->with('success', $message);
        }

        return back()->with('error', __('Error! Please try again.'));
    }

    /**
     * mark as viewed bulk questions
     *
     * @param array $questions
     * @return string
     */
    protected function markAsViewedBulkQuestions(array $questions)
    {
        $user = auth()->guard('seller')->user();
        $total_questions = StoreQuestion::whereStoreId($user->store_id)->whereStatus(StoreQuestion::NEW)->whereIn('id', $questions)->update(['status' => StoreQuestion::VIEWED]);
        // TODO Not sure if this is done correctly
        return __(':total question(s) mark as viewed.', ['total' => $total_questions]);
    }

    /**
     * archive bulk questions
     *
     * @param array $questions
     * @return string
     */
    protected function archiveBulkQuestions(array $questions)
    {
        $user = auth()->guard('seller')->user();
        $total_questions = StoreQuestion::whereStoreId($user->store_id)->whereIn('id', $questions)->update(['is_archive' => true]);

        return __(':total question(s) moved to archive.', ['total' => $total_questions]);
    }

    /**
     * delete bulk questions
     *
     * @param array $questions
     * @return string
     */
    protected function deleteBulkQuestions(array $questions)
    {
        $user = auth()->guard('seller')->user();
        $total_questions = StoreQuestion::whereStoreId($user->store_id)->whereIn('id', $questions)->count();
        StoreQuestion::whereIn('id', $questions)->delete();

        return __(':total question(s) deleted successfully.', ['total' => $total_questions]);
    }

    /**
     * Display a listing of archived the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewArchived()
    {
        $user = auth()->guard('seller')->user();
        $records = StoreQuestion::whereStoreId($user->store_id)->whereIsArchive(true)->latest()->get();

        return view('seller.store-questions.archived', [
            'title' => __('Archived Questions'),
            'records' => $records
        ]);
    }

    /**
     * Show the detail page for the specified resource.
     *
     * @param StoreQuestion $question
     * @return \Illuminate\Http\Response
     */
    public function viewArchivedDetail(StoreQuestion $question)
    {
        // authorize user
        $this->authorize('canView', $question);

        return view('seller.store-questions.archived-detail', [
            'title' => __('Detail'),
            'section_title' => __('Archived Questions'),
            'row' => $question
        ]);
    }
}
