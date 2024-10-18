<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FAQ;
use Illuminate\Http\Request;

class FAQController extends Controller
{
    public function index()
    {
        return view('admin.faq.index');
    }

    public function show()
    {

        return true;
    }

    public function getAll()
    {
        $customers = FAQ::select(['id', 'question', 'answer', 'status']);

        return datatables()->of($customers)
            ->addColumn('action', function (FAQ $q) {
                return "<a class='btn btn-sm btn-info' href='" . route('admin.faq.edit', [$q->id]) . "'>Edit</a>  &nbsp; &nbsp;" .
                    '<button class="btn btn-sm btn-danger" data-message="Are you sure you want to delete this FAQ?"
                                        data-action=' . route('admin.faq.destroy', [$q]) . '
                                        data-input={"_method":"delete"}
                                        data-toggle="modal" data-target="#modal-confirm">Delete</button>';
            })
            ->rawColumns(['action'])
            ->toJson();
    }

    public function create()
    {

        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
        ]);

        $question = $request->question;
        $answer = $request->answer;
        $status = $request->status;

        foreach ($question as $key => $i) {
            $faq = new FAQ();
            $faq->question = $question[$key];
            $faq->answer = $answer[$key];
            $faq->status = $status[$key];
            $faq->save();
        }

        return redirect()->route('admin.faq.index')->with('success', trans('admin.faq_created'));
    }

    public function edit(FAQ $faq)
    {

        $data['faq'] = $faq;
        return view('admin.faq.edit', $data);
    }

    public function update(FAQ $faq, Request $request)
    {
        $request->validate([
            'question' => 'required',
            'answer' => 'required',
            'status' => 'required|in:active,inactive',
        ]);
        $question = $request->question;
        $answer = $request->answer;
        $status = $request->status;

        $faq->question = $question;
        $faq->answer = $answer;
        $faq->status = $status;
        $faq->save();

        $new_question = $request->new_question;
        $new_answer = $request->new_answer;
        $new_status = $request->new_status;
        if ($new_question && $new_answer && $new_status) {
            foreach ($question as $key => $i) {
                $faq = new FAQ();
                $faq->question = $question[$key];
                $faq->answer = $answer[$key];
                $faq->status = $status[$key];
                $faq->save();
            }
        }


        return redirect()->route('admin.faq.index')->with('success', trans('admin.faq_updated'));
    }

    public function destroy(FAQ $faq)
    {
        $faq->delete();
        return redirect()->route('admin.faq.index')->with('success', trans('admin.faq_deleted'));
    }
}
