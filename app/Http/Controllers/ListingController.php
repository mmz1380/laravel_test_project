<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ListingController extends Controller
{
    public function index()
    {
        return view('listings.index', [
            'listings' => Listing::latest()
                ->filter(request(['tag', 'search']))
                ->paginate(6),
        ]);
    }

    public function show(Listing $listing)
    {
        return view('listings.show', [
            'listing' => $listing,
        ]);
    }

    public function create(Listing $listing)
    {
        return view('listings.create', [
            'listing' => $listing,
        ]);
    }

    public function store(Request $request)
    {
        $formField = $request->validate([
            'title' => ['required'],
            'company' => ['required', Rule::unique('listings', 'company')],
            'location' => ['required'],
            'website' => ['required'],
            'email' => ['required', 'email'],
            'tags' => ['required'],
            'description' => ['required'],
        ]);

        if ($request->hasFile('logo')) {
            $formField['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $formField['user_id'] = auth()->id();

        Listing::create($formField);

        return redirect('/')->with('message', 'Listing created successfully!');
    }

    public function edit(Listing $listing)
    {
        // dd($listing->location);
        return view('listings.edit', [
            'listing' => $listing,
        ]);
    }

    public function update(Request $request, Listing $listing)
    {
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action!');
        }

        $formField = $request->validate([
            'title' => ['required'],
            'company' => ['required'],
            'location' => ['required'],
            'website' => ['required'],
            'email' => ['required', 'email'],
            'tags' => ['required'],
            'description' => ['required'],
        ]);

        if ($request->hasFile('logo')) {
            $formField['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $listing->update($formField);

        return redirect('/')->with('message', 'Listing updated successfully!');
    }

    public function destroy(Listing $listing)
    {
        if ($listing->user_id != auth()->id()) {
            abort(403, 'Unauthorized Action!');
        }

        $listing->delete();
        return redirect('/')->with('message', 'Listing deleted successfully!');
    }

    public function manage(Listing $listing)
    {
        $listing->delete();
        return view('listings.show', [
            'listings' => auth()
                ->user()
                ->listings()
                ->get(),
        ]);
    }
}
