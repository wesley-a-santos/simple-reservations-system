<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\User;
use App\Models\Company;
use App\Models\Activity;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CompanyActivityController extends Controller
{
    public function index(Company $company)
    {
        $this->authorize('viewAny', $company);
        $company->load('activities');
        return view('companies.activities.index', compact('company'));
    }

    public function create(Company $company)
    {
        $this->authorize('create', $company);
        $guides = User::where('company_id', $company->id)
            ->where('role_id', Role::GUIDE->value)
            ->pluck('name', 'id');

        return view('companies.activities.create', compact('guides', 'company'));
    }

    public function store(StoreActivityRequest $request, Company $company)
    {
        $this->authorize('create', $company);

        $filename = $this->uploadImage($request);

        $activity = Activity::create($request->validated() + [
                'company_id' => $company->id,
                'photo' => $filename,
            ]);

        return to_route('companies.activities.index', $company);
    }

    public function edit(Company $company, Activity $activity)
    {
        $this->authorize('update', $company);

        $guides = User::where('company_id', $company->id)
            ->where('role_id', Role::GUIDE->value)
            ->pluck('name', 'id');

        return view('companies.activities.edit', compact('guides', 'activity', 'company'));
    }

    public function update(UpdateActivityRequest $request, Company $company, Activity $activity)
    {
        $this->authorize('update', $company);

        $filename = $this->uploadImage($request);

        $activity->update($request->validated() + [
                'photo' => $filename ?? $activity->photo,
            ]);

        return to_route('companies.activities.index', $company);
    }

    public function destroy(Company $company, Activity $activity)
    {
        $this->authorize('delete', $company);

        $activity->delete();

        return to_route('companies.activities.index', $company);
    }

    private function uploadImage(StoreActivityRequest|UpdateActivityRequest $request): string|null
    {
        if (! $request->hasFile('image')) {
            return null;
        }

        $filename = $request->file('image')->store(options: 'activities');

        $manager = new ImageManager(new Driver());

        $image = $manager->read(Storage::disk('activities')->get($filename))->scale(274, 274);

        Storage::disk('activities')->put('thumbs/' . $request->file('image')->hashName(), $image->toPng());

        return $filename;
    }
}
