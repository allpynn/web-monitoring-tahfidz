@props(['role' => 'parent'])

<aside id="sidebar" class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-100 sm:translate-x-0 dark:bg-gray-800/80 dark:backdrop-blur-xl dark:border-gray-700/50 shadow-sm" aria-label="Sidebar">
   <div class="h-full px-4 pb-4 overflow-y-auto bg-white dark:bg-transparent">
      <ul class="space-y-2 font-medium">
         <li>
            <a href="{{ route($role . '.dashboard') }}" class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs($role . '.dashboard') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                  <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-.066h.002Z"/>
                  <path d="M12.5 0c-.157 0-.311.01-.462.03a.999.999 0 0 0-.853.853 8.665 8.665 0 0 1 7.115 7.115.999.999 0 0 0 .853-.853A8.665 8.665 0 0 0 12.5 0Z"/>
               </svg>
               <span class="ms-3">Dashboard</span>
            </a>
         </li>

         @if($role === 'admin')
         <li>
            <a href="{{ route('admin.students.index') }}" class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('admin.students.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 18 20">
                  <path d="M17 5.923A1 1 0 0 0 16 5h-3V4a4 4 0 1 0-8 0v1H2a1 1 0 0 0-1 .923L.086 17.846A2 2 0 0 0 2.08 20h13.84a2 2 0 0 0 1.994-2.153L17 5.923ZM7 9a1 1 0 0 1-2 0V7h2v2Zm0-5a2 2 0 1 1 4 0v1H7V4Zm6 5a1 1 0 1 1-2 0V7h2v2Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Data Santri</span>
            </a>
         </li>
         <li>
            <a href="{{ route('admin.parents.index') }}" class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('admin.parents.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Data Orang Tua</span>
            </a>
         </li>
         @endif

         @if($role === 'guru')
         <li>
            <a href="{{ route('guru.students.index') }}" class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('guru.students.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Data Santri</span>
            </a>
         </li>
         <li>
            <a href="{{ route('guru.hafalan.index') }}" class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('guru.hafalan.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="m17.418 3.623-.018-.008a6.713 6.713 0 0 0-2.4-.569V2h1a1 1 0 1 0 0-2h-2a1 1 0 0 0-1 1v2H9.89A6.977 6.977 0 0 1 12 8v5h-2V8A5 5 0 1 0 0 8v6a1 1 0 0 0 1 1h8v4a1 1 0 0 0 1 1h2a1 1 0 0 0 1-1v-4h6a1 1 0 0 0 1-1V8a5 5 0 0 0-2.582-4.377ZM6 12H4a1 1 0 0 1 0-2h2a1 1 0 0 1 0 2Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Input Hafalan</span>
            </a>
         </li>
         @endif

         @if($role === 'parent')
         <li>
            <a href="{{ route('parent.history.index') }}" class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('parent.history.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z"/>
                  <path d="M6.737 11.062a1 1 0 0 1-1.077.307L2 10.237V20h16V6.112l-5.18-5.18a3 3 0 0 0-2.122-.879H7c-.017 0-.033.003-.05.003l.001 5a2 2 0 0 1-2 2h-5c.003.017.005.033.005.05v.237l4.062 1.354a1 1 0 0 1 .67.951l-.05 5.05h.001Zm3.519-3.264a1 1 0 1 1 1.414 1.414L10.414 10.5l1.256 1.256a1 1 0 1 1-1.414 1.414L9 11.914l-1.256 1.256a1 1 0 1 1-1.414-1.414l1.256-1.256-1.256-1.256a1 1 0 1 1 1.414-1.414L9 9.086l1.256-1.256Z"/>
               </svg>
               <span class="flex-1 ms-3 whitespace-nowrap">Riwayat Hafalan</span>
            </a>
         </li>
         @endif
      </ul>
      
      <ul class="pt-4 mt-4 space-y-2 font-medium border-t border-gray-100 dark:border-gray-700">
         <li>
            <a href="{{ route('profile.edit') }}" class="flex items-center p-3 text-gray-900 rounded-2xl dark:text-white hover:bg-emerald-50 dark:hover:bg-emerald-900/30 group {{ request()->routeIs('profile.*') ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400 font-bold' : '' }}">
               <svg class="flex-shrink-0 w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-emerald-700 dark:group-hover:text-emerald-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                  <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z"/>
               </svg>
               <span class="ms-3">Profil Saya</span>
            </a>
         </li>
         <li>
            <form method="POST" action="{{ route('logout') }}">
               @csrf
               <button type="submit" class="w-full flex items-center p-3 text-red-600 rounded-2xl hover:bg-red-50 dark:hover:bg-red-900/20 group transition-colors font-bold">
                  <svg class="flex-shrink-0 w-5 h-5 text-red-500 transition duration-75 group-hover:text-red-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 18 16">
                     <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M1 8h11m0 0L8 4m4 4-4 4m4-11h3a2 2 0 0 1 2 2v10a2 2 0 0 1-2 2h-3"/>
                  </svg>
                  <span class="ms-3">Keluar</span>
               </button>
            </form>
         </li>
      </ul>
   </div>
</aside>
