<?php

namespace App\Http\Controllers\Backstage;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backstage\Prizes\StoreRequest;
use App\Http\Requests\Backstage\Prizes\UpdateRequest;
use App\Models\Prize;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PrizeController extends Controller
{
    /**
     * Display a listing of the prizes.
     */
    public function index(): View
    {
        return view('backstage.prizes.index');
    }

    /**
     * Show the form for creating a new prize.
     */
    public function create(): View
    {
        return view('backstage.prizes.create', [
            'prize' => new Prize,
        ]);
    }

    /**
     * Store a newly created prize in storage.
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['campaign_id'] = session('activeCampaign');

        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = public_path('assets'); 
    
        if (!file_exists($imagePath)) {
            mkdir($imagePath, 0777, true);
        }
    
        $image->move($imagePath, $imageName);
    
        $data['image'] = $imageName;
        
        Prize::create($data);

        session()->flash('success', 'The prize has been created!');

        return redirect()->route('backstage.prizes.index');
    }

    /**
     * Show the form for editing the specified prize.
     */
    public function edit(Prize $prize): View
    {
        return view('backstage.prizes.edit', [
            'prize' => $prize,
        ]);
    }

    /**
     * Update the specified prize in storage.
     */
    public function update(UpdateRequest $request, Prize $prize): RedirectResponse
    {
        $data = $request->validated();
        $data['campaign_id'] = session('activeCampaign');
       
        $image = $request->file('image');
        $imageName = time() . '_' . $image->getClientOriginalName(); 
        $imagePath = public_path('assets'); 
    
        if (!file_exists($imagePath)) {
            mkdir($imagePath, 0777, true);
        }
    
        $image->move($imagePath, $imageName);
    
        $data['image'] = 'assets/' . $imageName;

        $prize->update($data);

        session()->flash('success', 'The prize has been updated!');

        return redirect()->route('backstage.prizes.edit', $prize->id);
    }

    /**
     * Remove the specified prize from storage.
     */
    public function destroy(Prize $prize): RedirectResponse
    {
        $prize->delete();

        session()->flash('success', 'The prize has been deleted!');

        return redirect()->route('backstage.prizes.index');
    }
}
