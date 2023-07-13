<?php

namespace App\Http\Controllers;

use App\Contracts\Actions\ZipPacker;
use App\Models\Attachment;
use App\Models\Book;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{
    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'images' => 'required|array',
            'images.*' => 'required|image',
        ]);

        $images = [];

        $max = $project->attachments()->max('order_num');

        foreach ($request->file('images') as $image) {

            $path = $image->store('attachments');

            $images[] = [
                'filename' => $image->getClientOriginalName(),
                'path' => $path,
                'order_num' => ++$max,
            ];
        }

        $project->attachments()->createMany($images);

        return back()->with('status', 'Images added.');
    }

    public function sort(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'order' => 'required|array',
            'order.*' => 'required|integer|exists:attachments,id',
        ]);

        $upsert = [];
        $order = $request->input('order');

        foreach ($project->attachments as $attachment) {
            $upsert[] = [
                'id' => $attachment->id,
                'filename' => $attachment->filename,
                'path' => $attachment->path,
                'order_num' => array_search($attachment->id, $order) + 1,
            ];
        }

        Attachment::upsert($upsert, 'id');

        return back()->with('status', 'Images sorted.');
    }

    public function destroy(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'delete' => 'required|array',
            'delete.*' => 'required|in:on',
        ], [
            'delete.required' => 'No images selected.',
        ]);

        $names = [];

        $project->attachments->whereIn('id', array_keys($request->input('delete')))
            ->each(function ($attachment) use (&$names) {
                Storage::delete($attachment->path);

                $names[] = $attachment->filename;

                $attachment->delete();
            });

        return back()->with('status', implode(', ', $names).' deleted.');
    }

    public function epub(ZipPacker $packer, Project $project)
    {
        $this->authorize('update', $project);

        validator(
            $project->only('book_title'),
            ['book_title' => 'required'],
            ['book_title.required' => 'Book title is required.']
        )->validated();

        $paths = [];
        foreach ($project->attachments->sortBy('order_num') as $attachment) {
            $paths[] = $attachment->path;
        }

        $zip = $packer->pack($project->book_title, $paths, [
            'identifier' => $project->book_id,
            'language' => $project->book_language,
            'page-id' => 'page',
            'title' => $project->book_title,
            'author' => $project->book_creator,
            'publisher' => $project->book_publisher,
            'subject' => $project->book_subject,
            'width' => $project->book_width,
            'height' => $project->book_height,
            'pager' => $project->book_webtoon_style ? 'yes' : 'no',
        ]);

        $project->books()->create([
            'name' => Str::slug($project->book_title).'-'.now()->timestamp.'.epub',
            'path' => $zip,
        ]);

        return back()->with('status', 'Epub created.');
    }

    public function destroyEpub(Project $project)
    {
        $this->authorize('update', $project);

        if ($project->books()->exists()) {
            $project->books->each(
                fn ($book) => $book->delete(),
            );
        }

        return back()->with('status', 'Epub cleared.');
    }

    public function download(Project $project, Book $book)
    {
        $this->authorize('view', $project);

        return Storage::download($book->path, $book->name);
    }
}
