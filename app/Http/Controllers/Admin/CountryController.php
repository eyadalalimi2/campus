<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CountryRequest;
use App\Models\Country;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name_ar')->paginate(15);
        return view('admin.countries.index', compact('countries'));
    }

    public function create()
    {
        return view('admin.countries.create');
    }

    public function store(CountryRequest $request)
    {
        $data = $request->validated();
        Country::create($data);
        return redirect()->route('admin.countries.index')->with('success','تم إضافة الدولة بنجاح.');
    }

    public function edit(Country $country)
    {
        return view('admin.countries.edit', compact('country'));
    }

    public function update(CountryRequest $request, Country $country)
    {
        $data = $request->validated();
        $country->update($data);
        return redirect()->route('admin.countries.index')->with('success','تم تحديث الدولة بنجاح.');
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return redirect()->route('admin.countries.index')->with('success','تم حذف الدولة بنجاح.');
    }
}
