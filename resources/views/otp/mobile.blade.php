<x-layout>
    <x-card class="mx-auto mt-24 max-w-lg p-10">
        <header class="text-center">
            <h2 class="mb-1 text-2xl font-bold uppercase">
                OTP
            </h2>
        </header>

        <form method="POST" action="/otp">
            @csrf
            <div class="mb-6">
                <label for="mobile_no" class="mb-2 inline-block text-lg">
                    Mobile Number
                </label>
                <input type="text" class="w-full rounded border border-gray-200 p-2"
                       name="mobile_no" value="{{ old('mobile_no') }}" />
                @error('mobile_no')
                <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <button type="submit"
                        class="bg-laravel rounded py-2 px-4 text-white hover:bg-black">
                    Send my OTP
                </button>
            </div>

            <div class="mt-8">
                <p>
                    Already have an account?
                    <a href="/login" class="text-laravel">Login</a>
                </p>
            </div>
        </form>
    </x-card>
</x-layout>
