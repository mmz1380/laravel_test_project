<x-layout>
    <x-card class="p-10">
        <header>
            <h1 class="my-6 text-center text-3xl font-bold uppercase">
                Manage Gigs
            </h1>
        </header>

        <table class="w-full table-auto rounded-sm">
            <tbody>
                @unless ($listings->isEmpty())
                    @foreach ($listings as $listing)
                        <tr class="border-gray-300">
                            <td class="border-t border-b border-gray-300 px-4 py-8 text-lg">
                                <a href="/listings/{listing}">
                                    {{ $listing->title }}
                                </a>
                            </td>
                            <td class="border-t border-b border-gray-300 px-4 py-8 text-lg">
                                <a href="/listings/{{ $listing->id }}/edit"
                                    class="rounded-xl px-6 py-2 text-blue-400"><i
                                        class="fa-solid fa-pen-to-square"></i>
                                    Edit</a>
                            </td>
                            <td class="border-t border-b border-gray-300 px-4 py-8 text-lg">
                                <form method="POST" action="/listings/{{ $listing->id }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-600">
                                        <i class="fa-solid fa-trash-can"></i>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="border-gray-300">
                        <td class="border-t border-b border-gray-300 px-4 py-8 text-lg">
                            <p class="text-center">
                                No Listings Found!
                            </p>
                        </td>
                    </tr>
                @endunless
            </tbody>
        </table>
    </x-card>
</x-layout>
