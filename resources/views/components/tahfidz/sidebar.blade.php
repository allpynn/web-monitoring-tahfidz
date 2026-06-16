@props(['role' => 'parent'])

<aside id="sidebar"
   class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-100 sm:translate-x-0 dark:bg-gray-800/80 dark:backdrop-blur-xl dark:border-gray-700/50 shadow-sm"
   aria-label="Sidebar">
   <div class="h-full px-4 pb-4 overflow-y-auto bg-white dark:bg-transparent">
      <ul class="space-y-2 font-medium">
         <li>
            <a href="{{ route($role . '.dashboard') }}"
               class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs($role . '.dashboard') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 transition duration-75 text-gray-500 group-hover:text-emerald-700 dark:text-gray-400 dark:group-hover:text-emerald-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
                    <path d="M12 2.252A8.001 8.001 0 0117.748 8H12V2.252z" />
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>

         @if($role === 'admin')
            <li>
               <a href="{{ route('admin.students.index') }}"
                  class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('admin.students.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">
                     <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                     <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Data Santri</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.guru.index') }}"
                  class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('admin.guru.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">
                     <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                     <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Data Guru/Pengajar</span>
               </a>
            </li>
            <li>
               <a href="{{ route('admin.parents.index') }}"
                  class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('admin.parents.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">
                     <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                     <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308 5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Data Orang Tua</span>
               </a>
            </li>
         @endif

         @if($role === 'guru')
            <li>
               <a href="{{ route('guru.students.index') }}"
                  class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('guru.students.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">
                     <path fill-rule="evenodd" d="M8.25 6.75a3.75 3.75 0 1 1 7.5 0 3.75 3.75 0 0 1-7.5 0ZM15.75 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM2.25 9.75a3 3 0 1 1 6 0 3 3 0 0 1-6 0ZM6.31 15.117A6.745 6.745 0 0 1 12 12a6.745 6.745 0 0 1 6.709 7.498.75.75 0 0 1-.372.568A12.696 12.696 0 0 1 12 21.75c-2.305 0-4.47-.612-6.337-1.684a.75.75 0 0 1-.372-.568 6.787 6.787 0 0 1 1.019-4.38Z" clip-rule="evenodd" />
                     <path d="M5.082 14.254a8.287 8.287 0 0 0-1.308 5.135 9.687 9.687 0 0 1-1.764-.44l-.115-.04a.563.563 0 0 1-.373-.487l-.01-.121a3.75 3.75 0 0 1 3.57-4.047ZM20.226 19.389a8.287 8.287 0 0 0-1.308-5.135 3.75 3.75 0 0 1 3.57 4.047l-.01.121a.563.563 0 0 1-.373.486l-.115.04c-.567.2-1.156.349-1.764.441Z" />
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Daftar Santri</span>
               </a>
            </li>
            <li>
                <a href="{{ route('guru.hafalan.index') }}"
                   class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('guru.hafalan.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                   </svg>
                   <span class="flex-1 ms-3 whitespace-nowrap">Daftar Hafalan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('guru.messages') }}"
                   class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('guru.messages') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
                   <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859m-19.5.338V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H6.911a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661Z" />
                   </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Pesan</span>
                    @if(isset($unreadMessagesCount))
                       <span id="sidebar-unread-badge" class="{{ $unreadMessagesCount > 0 ? '' : 'hidden' }} inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 ms-2 text-[10px] font-black text-white bg-emerald-500 dark:bg-emerald-600 rounded-full shadow-md shadow-emerald-500/20">
                          {{ $unreadMessagesCount }}
                       </span>
                    @endif
                </a>
            </li>
         @endif

         @if($role === 'parent')
            <li>
               <a href="{{ route('parent.history.index') }}"
                  class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('parent.history.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400">
                     <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V6A2.25 2.25 0 0 1 6 3.75h1.5m9 0h-9" />
                  </svg>
                  <span class="flex-1 ms-3 whitespace-nowrap">Riwayat Hafalan</span>
               </a>
            </li>
         @endif
      </ul>

      <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-100 dark:border-gray-700">
         @if($role === 'admin')
            <li>
               <a href="{{ route('profile.edit') }}"
                  class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('profile.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
                  <svg class="flex-shrink-0 w-5 h-5 transition duration-75 text-gray-500 group-hover:text-emerald-700 dark:text-gray-400 dark:group-hover:text-emerald-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                     <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" />
                  </svg>
                  <span class="ms-3">Profil Saya</span>
               </a>
            </li>
         @endif
         <li>
            <form method="POST" action="{{ route('logout') }}">
               @csrf
               <button type="submit"
                  class="w-full flex items-center p-3 text-red-600 rounded-2xl hover:bg-red-50 dark:hover:bg-red-900/20 group transition-colors font-bold">
                  <svg class="flex-shrink-0 w-5 h-5 text-red-500 transition duration-75 group-hover:text-red-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                  </svg>
                  <span class="ms-3">Keluar</span>
               </button>
            </form>
         </li>
      </ul>
   </div>
</aside>