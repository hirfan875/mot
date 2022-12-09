<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactInquiry;
use App\Models\ContactResponse;
use App\Service\ContactInquiryService;
use Illuminate\Support\Facades\Lang;

class ContactInquiriesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = ContactInquiry::whereIsArchive(false)->latest()->get();

        return view('admin.contact-inquiries.index', [
            'title' => __('Contact Inquiries'),
            'records' => $records
        ]);
    }

    /**
     * Show the detail page for the specified resource.
     *
     * @param ContactInquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function detail(ContactInquiry $inquiry)
    {
        // update inquiry status
        $inquiry->status = ContactInquiry::VIEWED;
        $inquiry->save();
        $replies = ContactResponse::where('contact_inquiry_id' , $inquiry->id )->get();

        return view('admin.contact-inquiries.detail', [
            'title' => __('Detail'),
            'section_title' => __('Contact Inquiries'),
            'row' => $inquiry,
            'replies' => $replies
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param ContactInquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, ContactInquiry $inquiry)
    {
        $request->validate([
            'subject' => 'required',
            'message' => 'required',
        ]);

        $contactInquiryService = new ContactInquiryService();
        $response = $contactInquiryService->sendReply($request->toArray(), $inquiry);

        return redirect()->route('admin.contact.inquiries')->with('success', $response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param ContactInquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function delete(ContactInquiry $inquiry)
    {
        $inquiry->delete();
        return back()->with('success', __('Record deleted successfully.'));
    }

    /**
     * Archive the specified resource from storage.
     *
     * @param ContactInquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function archive(ContactInquiry $inquiry)
    {
        $inquiry->is_archive = true;
        $inquiry->save();
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
            'inquiries' => 'required|array|min:1'
        ], [
            'inquiries.required' => 'You must select at least 1 inquiry.'
        ]);

        if ($request->bulk_action === 'viewed') {
            $message = $this->markAsViewedBulkInquiries($request->inquiries);
            return back()->with('success', $message);
        }

        if ($request->bulk_action === 'archive') {
            $message = $this->archiveBulkInquiries($request->inquiries);
            return back()->with('success', $message);
        }

        if ($request->bulk_action === 'delete') {
            $message = $this->deleteBulkInquiries($request->inquiries);
            return back()->with('success', $message);
        }

        return back()->with('error', __('Error! Please try again.'));
    }

    /**
     * mark as viewed bulk inquiries
     *
     * @param array $inquiries
     * @return string
     */
    protected function markAsViewedBulkInquiries(array $inquiries)
    {
        $total_inquiries = ContactInquiry::whereStatus(ContactInquiry::NEW)->whereIn('id', $inquiries)->update(['status' => ContactInquiry::VIEWED]);
        return __(':total inquiry(s) mark as viewed.', ['total' => $total_inquiries]);
    }

    /**
     * archive bulk inquiries
     *
     * @param array $inquiries
     * @return string
     */
    protected function archiveBulkInquiries(array $inquiries)
    {
        $total_inquiries = ContactInquiry::whereIn('id', $inquiries)->update(['is_archive' => true]);
        return __(':total inquiry(s) moved to archive.', ['total' => $total_inquiries]);
    }

    /**
     * delete bulk inquiries
     *
     * @param array $inquiries
     * @return string
     */
    protected function deleteBulkInquiries(array $inquiries)
    {
        $total_inquiries = ContactInquiry::whereIn('id', $inquiries)->count();
        ContactInquiry::whereIn('id', $inquiries)->delete();
        return __(':total inquiry(s) deleted successfully.', ['total' => $total_inquiries]);
    }

    /**
     * Display a listing of archived the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function viewArchived()
    {
        $records = ContactInquiry::whereIsArchive(true)->latest()->get();

        return view('admin.contact-inquiries.archived', [
            'title' => __('Archived'),
            'records' => $records
        ]);
    }

    /**
     * Show the detail page for the specified resource.
     *
     * @param ContactInquiry $inquiry
     * @return \Illuminate\Http\Response
     */
    public function viewArchivedDetail(ContactInquiry $inquiry)
    {
        return view('admin.contact-inquiries.archived-detail', [
            'title' => __('Detail'),
            'section_title' => __('Archived'),
            'row' => $inquiry
        ]);
    }
}
