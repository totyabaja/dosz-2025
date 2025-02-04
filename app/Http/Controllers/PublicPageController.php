<?php

namespace App\Http\Controllers;

use App\Models\Aid\FAQ as AidFAQ;
use App\Models\Blog;
use App\Models\Event;
use App\Models\Scientific;
use App\Models\Aid;
use App\Models\Menu;
use App\Models\ScientificDepartment;
use Illuminate\Http\Request;
use App\Models\Scientific\ScientificDepartment as ScientificScientificDepartment;
use Illuminate\Support\Facades\Auth;
use TotyaDev\TotyaDevMediaManager\Models\{Folder, Media};

class PublicPageController extends Controller
{
    public function home()
    {
        $slides = Blog\Post::query()
            ->whereNotNull('name->' . session()->get('locale', 'hu'))
            ->whereNull('scientific_department_id')
            ->latest()
            ->paginate(3);
        // $rendezvenyek = Post::query()->latest()->paginate(3);

        return view('filament.pages.public.kezdolap', compact('slides'));
    }

    public function show($slug)
    {
        $page = Menu\Page::where('slug', $slug)
            ->orderBy('version', 'desc')
            ->firstOrFail();

        return view('filament.pages.public.show', compact('page'));
    }

    // TODO
    public function to_show($to_slug, $slug)
    {
        $page = Menu\Page::where('slug', $slug)
            ->orderBy('version', 'desc')
            ->firstOrFail();

        return view('filament.pages.public.to-show', compact('to_slug', 'page'));
    }

    public function hirek()
    {
        $hirek = Blog\Post::query()
            ->whereNotNull('name->' . session()->get('locale', 'hu'))
            ->whereNull('scientific_department_id')
            ->orderByDesc('created_at')
            ->paginate(12);

        return view('filament.pages.public.hirek', compact('hirek'));
    }

    public function hir($slug)
    {
        $hir = Blog\Post::where('slug', $slug)->first();

        return view('filament.pages.public.hir', compact('hir'));
    }

    // TODO
    public function to_hirek($to_slug)
    {
        $hirek = Blog\Post::query()
            ->whereNotNull('name->' . session()->get('locale', 'hu'))
            ->where('scientific_department_id', ScientificScientificDepartment::where('slug', $to_slug)->first()->id)
            ->get();

        return view('filament.pages.public.to-hirek', compact('to_slug', 'hirek'));
    }

    // TODO
    public function to_hir($to_slug, $slug)
    {
        $hir = Blog\Post::where('slug', $slug)->first();

        return view('filament.pages.public.to-hir', compact('to_slug', 'hir'));
    }

    public function jogsegely()
    {
        return view('filament.pages.public.jogsegely');
    }

    public function gyik()
    {

        $faqs = Aid\FAQ::query()
            ->where('language', session()->get('locale'))
            ->orderBy('question')
            ->get();

        return view('filament.pages.public.gyik', compact('faqs'));
    }

    public function alt_ker()
    {

        // TODO: nem ez kell ide
        $faqs = Aid\GeneralQuestion::query()
            ->where('language', session()->get('locale'))
            ->orderBy('question')
            ->get();

        return view('filament.pages.public.alt_ker', compact('faqs'));
    }

    public function tok()
    {
        $tok = Scientific\ScientificDepartment::query()
            ->get()
            ->sortBy('filament_name');

        return view('filament.pages.public.tok', compact('tok'));
    }

    // TODO
    public function to($to_slug)
    {
        $slides = Blog\Post::query()
            ->whereNotNull('name->' . session()->get('locale', 'hu'))
            ->latest()
            ->paginate(3);


        $dosz_hirek =
            Blog\Post::query()
            ->whereNotNull('name->' . session()->get('locale', 'hu'))
            ->whereNull('scientific_department_id')
            ->get();

        return view('filament.pages.public.to-kezdolap', compact('to_slug', 'slides', 'dosz_hirek'));
    }

    public function rendezvenyek()
    {
        $rendezvenyek = Event\Event::query()
            ->whereNotNull('name->' . session()->get('locale', 'hu'))
            ->get();

        return view('filament.pages.public.rendezvenyek', compact('rendezvenyek'));
    }

    public function rendezveny($slug)
    {
        $rendezveny = Event\Event::where('slug', $slug)->first();

        return view('filament.pages.public.rendezveny', compact('rendezveny'));
    }

    // TODO
    public function to_rendezvenyek($to_slug)
    {
        $rendezvenyek = Event\Event::query()
            ->whereNotNull('name->' . session()->get('locale', 'hu'))
            ->get();

        return view('filament.pages.public.to-rendezvenyek', compact('to_slug', 'rendezvenyek'));
    }

    // TODO
    public function to_rendezveny($to_slug, $slug)
    {
        $rendezveny = Event\Event::where('slug', $slug)->first();

        return view('filament.pages.public.to-rendezveny', compact('to_slug', 'rendezveny'));
    }

    public function dokumentumok(?string $folder = 'publikus-allomanyok')
    {
        $main_folder = Folder::query()
            ->where('is_public', true)
            ->where('collection', $folder)
            ->first();

        $medium = Media::query()
            ->where('collection_name', $main_folder->collection)
            ->get();

        return view('filament.pages.public.dokumentumok', compact('main_folder', 'medium'));
    }

    // TODO
    public function to_dokumentumok($to_slug, ?string $folder = null)
    {

        $main_folder = Scientific\ScientificDepartment::where('slug', $to_slug)->first()?->folders->first();

        if ($folder)
            $main_folder = Folder::query()
                ->where('is_public', true)
                ->where('collection', $folder)
                ->first();

        $medium = Media::query()
            ->where('collection_name', $main_folder->collection)
            ->get();

        return view('filament.pages.public.to-dokumentumok', compact('to_slug', 'main_folder', 'medium'));
    }
}
