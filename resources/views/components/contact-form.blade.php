@if(session('success'))
<div class="mb-6 p-4 rounded-brand bg-light-green/20 border border-light-green text-dark-green text-sm">
    {{ session('success') }}
</div>
@endif

<form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
    @csrf

    <div>
        <label for="name" class="block text-sm font-heading font-medium text-dark-text mb-1.5">Nama</label>
        <input type="text" id="name" name="name" value="{{ old('name') }}" required
               class="w-full px-4 py-3 rounded-brand border border-gray-200 focus:border-primary-green focus:ring-1 focus:ring-primary-green outline-none transition-colors @error('name') border-red-400 @enderror">
        @error('name') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label for="email" class="block text-sm font-heading font-medium text-dark-text mb-1.5">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-4 py-3 rounded-brand border border-gray-200 focus:border-primary-green focus:ring-1 focus:ring-primary-green outline-none transition-colors @error('email') border-red-400 @enderror">
            @error('email') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="phone" class="block text-sm font-heading font-medium text-dark-text mb-1.5">Nomor Telepon</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                   class="w-full px-4 py-3 rounded-brand border border-gray-200 focus:border-primary-green focus:ring-1 focus:ring-primary-green outline-none transition-colors @error('phone') border-red-400 @enderror">
            @error('phone') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
        </div>
    </div>

    <div>
        <label for="subject" class="block text-sm font-heading font-medium text-dark-text mb-1.5">Subjek</label>
        <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required
               class="w-full px-4 py-3 rounded-brand border border-gray-200 focus:border-primary-green focus:ring-1 focus:ring-primary-green outline-none transition-colors @error('subject') border-red-400 @enderror">
        @error('subject') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    <div>
        <label for="message" class="block text-sm font-heading font-medium text-dark-text mb-1.5">Pesan</label>
        <textarea id="message" name="message" rows="5" required
                  class="w-full px-4 py-3 rounded-brand border border-gray-200 focus:border-primary-green focus:ring-1 focus:ring-primary-green outline-none transition-colors resize-none @error('message') border-red-400 @enderror">{{ old('message') }}</textarea>
        @error('message') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
    </div>

    <button type="submit" class="w-full inline-flex items-center justify-center px-8 py-3.5 rounded-brand bg-primary-green text-white font-heading font-semibold hover:bg-dark-green transition-all duration-300">
        Kirim Pesan
    </button>
</form>
