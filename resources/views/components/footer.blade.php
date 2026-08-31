<footer class="bg-dark-green text-white/90">
    <div class="max-w-7xl mx-auto px-6 lg:px-8 py-16">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">

            <!-- Logo & Deskripsi -->
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" class="w-8 h-8 text-light-green">
                        <path fill="currentColor" d="M12 2c-3 3-5 7-5 10.5A5 5 0 0012 18a5 5 0 005-5.5C17 9 15 5 12 2z"/>
                        <path stroke="#D4A017" stroke-width="1.5" d="M12 18v4"/>
                    </svg>
                    <span class="font-heading font-bold text-lg text-white">PT. Sahabat Sawit Rokan Sejahtera</span>
                </div>
                <p class="text-sm text-white/70 leading-relaxed">
                    Perusahaan perkebunan kelapa sawit yang tumbuh dan berkembang di Kabupaten Rokan Hilir, Provinsi Riau, dengan komitmen pada produktivitas dan keberlanjutan.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="font-heading font-semibold text-white mb-4">Tautan Cepat</h3>
                <ul class="space-y-2 text-sm text-white/70">
                    <li><a href="{{ route('about') }}" class="hover:text-gold transition-colors">Tentang Kami</a></li>
                    <li><a href="{{ route('plantation') }}" class="hover:text-gold transition-colors">Perkebunan</a></li>
                    <li><a href="{{ route('products') }}" class="hover:text-gold transition-colors">Produk</a></li>
                    <li><a href="{{ route('sustainability') }}" class="hover:text-gold transition-colors">Keberlanjutan</a></li>
                    <li><a href="{{ route('career') }}" class="hover:text-gold transition-colors">Karier</a></li>
                </ul>
            </div>

            <!-- Kontak -->
            <div>
                <h3 class="font-heading font-semibold text-white mb-4">Kontak</h3>
                <ul class="space-y-3 text-sm text-white/70">
                    <li class="flex gap-2">
                        <span>Jl. Perkebunan Sawit No. 1, Kabupaten Rokan Hilir, Riau, Indonesia (alamat lengkap — placeholder)</span>
                    </li>
                    <li class="flex gap-2"><span>info@sahabatsawit.co.id</span></li>
                </ul>
            </div>

            <!-- Sosial Media -->
            <div>
                <h3 class="font-heading font-semibold text-white mb-4">Ikuti Kami</h3>
                <div class="flex gap-3">
                    <a href="#" aria-label="Facebook PT Sahabat Sawit" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-gold transition-colors">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M22 12a10 10 0 10-11.6 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.4h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.4v7A10 10 0 0022 12z"/></svg>
                    </a>
                    <a href="#" aria-label="Instagram PT Sahabat Sawit" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-gold transition-colors">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M12 2c2.7 0 3 0 4.1.1 1.1 0 1.8.2 2.2.4.6.2 1 .5 1.4.9.4.4.7.8.9 1.4.2.4.3 1.1.4 2.2.1 1.1.1 1.4.1 4.1s0 3-.1 4.1c0 1.1-.2 1.8-.4 2.2-.2.6-.5 1-.9 1.4-.4.4-.8.7-1.4.9-.4.2-1.1.3-2.2.4-1.1.1-1.4.1-4.1.1s-3 0-4.1-.1c-1.1 0-1.8-.2-2.2-.4-.6-.2-1-.5-1.4-.9-.4-.4-.7-.8-.9-1.4-.2-.4-.3-1.1-.4-2.2C2 15 2 14.7 2 12s0-3 .1-4.1c0-1.1.2-1.8.4-2.2.2-.6.5-1 .9-1.4.4-.4.8-.7 1.4-.9.4-.2 1.1-.3 2.2-.4C8 2 8.3 2 12 2zm0 1.8c-2.7 0-3 0-4 .1-1 0-1.5.2-1.9.3-.5.2-.8.4-1.1.7-.3.3-.5.6-.7 1.1-.1.4-.3.9-.3 1.9-.1 1-.1 1.3-.1 4s0 3 .1 4c0 1 .2 1.5.3 1.9.2.5.4.8.7 1.1.3.3.6.5 1.1.7.4.1.9.3 1.9.3 1 .1 1.3.1 4 .1s3 0 4-.1c1 0 1.5-.2 1.9-.3.5-.2.8-.4 1.1-.7.3-.3.5-.6.7-1.1.1-.4.3-.9.3-1.9.1-1 .1-1.3.1-4s0-3-.1-4c0-1-.2-1.5-.3-1.9-.2-.5-.4-.8-.7-1.1-.3-.3-.6-.5-1.1-.7-.4-.1-.9-.3-1.9-.3-1-.1-1.3-.1-4-.1zM12 7a5 5 0 110 10 5 5 0 010-10zm0 1.8a3.2 3.2 0 100 6.4 3.2 3.2 0 000-6.4zm5.2-2a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"/></svg>
                    </a>
                    <a href="#" aria-label="YouTube PT Sahabat Sawit" class="w-10 h-10 rounded-full bg-white/10 flex items-center justify-center hover:bg-gold transition-colors">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24"><path d="M21.6 7.2s-.2-1.5-.8-2.1c-.8-.8-1.7-.8-2.1-.9C15.9 4 12 4 12 4h0s-3.9 0-6.7.2c-.4 0-1.3.1-2.1.9-.6.6-.8 2.1-.8 2.1S2.2 9 2.2 10.8v1.4c0 1.8.2 3.6.2 3.6s.2 1.5.8 2.1c.8.8 1.8.8 2.3.9 1.6.2 6.5.2 6.5.2s3.9 0 6.7-.2c.4 0 1.3-.1 2.1-.9.6-.6.8-2.1.8-2.1s.2-1.8.2-3.6v-1.4c0-1.8-.2-3.6-.2-3.6zM9.9 14.6V8.9l5.4 2.9-5.4 2.8z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-4 text-sm text-white/60">
            <p>&copy; {{ date('Y') }} PT Sahabat Sawit. All Rights Reserved.</p>
            <div class="flex gap-6">
                <a href="#" class="hover:text-gold transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-gold transition-colors">Terms & Conditions</a>
            </div>
        </div>
    </div>
</footer>
