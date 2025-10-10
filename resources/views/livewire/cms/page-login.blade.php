<div>
    {{-- The whole world belongs to you. --}}
    <div class="min-h-screen flex items-center justify-center bg-slate-50">
        <div class="w-full max-w-md bg-white p-6 rounded shadow">
            <h1 class="text-xl font-semibold mb-4">Login</h1>

            <form wire:submit.prevent="login" class="space-y-4">
                <div>
                    <label class="block text-sm mb-1">Email</label>
                    <input type="email" wire:model="email"
                        class="w-full border rounded px-3 py-2"
                        placeholder="Email">
                    @error('email') <p class="text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm mb-1">Password</label>
                    <input type="password" wire:model="password"
                        class="w-full border rounded px-3 py-2"
                        placeholder="Password">
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
                    Masuk
                </button>
            </form>
        </div>
    </div>

</div>