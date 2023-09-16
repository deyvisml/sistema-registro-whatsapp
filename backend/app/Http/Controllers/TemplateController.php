<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $templates = Template::all();

        $data = [
            "error_occurred" => false,
            "data" => [
                "templates" => $templates
            ],
        ];

        return response()->json($data, 200);
    }

    public function fields(Request $request)
    {
        $request->validate([
            "template_id" => "required|exists:templates,id"
        ]);

        $template_id = $request->input("template_id");
        $template = Template::findOrFail($template_id);

        $fields = $template->fields(); // getting fields of the current template

        $data = [
            "error_occurred" => false,
            "data" => [
                "fields" => $fields
            ]
        ];

        return response()->json($data, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
