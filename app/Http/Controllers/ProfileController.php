<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo'
            => 'required|image|mimes:jpg,jpeg,png,webp|max:2048|dimensions:min_width=200,min_height=200',
        ], [
            'photo.dimensions' => 'Ukuran foto profil minimal 200 × 200 piksel.',
        ]);

        $disk = Storage::disk('public');
        $newPhoto = $request->file('photo')->store('profiles', 'public');

        if ($newPhoto === false) {
            return back()->withErrors(['photo' => 'Foto baru gagal disimpan.']);
        }

        $user = $request->user();

        try {
            $oldPhoto = DB::transaction(function () use ($user, $newPhoto) {
                $currentUser = $user->newQuery()
                    ->lockForUpdate()
                    ->findOrFail($user->getKey());

                $oldPhoto = $currentUser->photo;
                $currentUser->photo = $newPhoto;
                $currentUser->save();

                return $oldPhoto;
            });
        } catch (\Throwable $error) {
            $disk->delete($newPhoto);
            throw $error;
        }

        if ($oldPhoto && $disk->exists($oldPhoto) && !$disk->delete($oldPhoto)) {
            return back()->withErrors([
                'photo' => 'Foto baru tersimpan, tetapi file foto lama gagal dihapus.',
            ]);
        }

        return redirect()->route('profile.edit')->with('status', 'photo-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
