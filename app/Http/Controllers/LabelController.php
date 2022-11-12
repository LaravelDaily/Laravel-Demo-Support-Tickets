<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Http\Requests\LabelRequest;

class LabelController extends Controller
{
    public function index()
    {
        $labels = Label::paginate();

        return view('labels.index', compact('labels'));
    }

    public function create()
    {
        return view('labels.create');
    }

    public function store(LabelRequest $request)
    {
        Label::create($request->validated());

        return to_route('labels.index');
    }

    public function show(Label $label) {}

    public function edit(Label $label)
    {
        return view('labels.edit', compact('label'));
    }

    public function update(LabelRequest $request, Label $label)
    {
        $label->update($request->validated());

        return to_route('labels.index');
    }

    public function destroy(Label $label)
    {
        $label->delete();

        return to_route('labels.index');
    }
}