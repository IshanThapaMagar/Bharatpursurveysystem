<x-app-layout>
    <div class="py-12 sm:py-24 px-4 sm:px-6 lg:px-8">

        <!-- Header Section -->
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    {{ __('Add Palika Admin') }}
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    {{ __('Register a new administrative official to the municipality system.') }}
                </p>
            </div>
            <a href="{{ route('palika.index') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                <svg class="mr-2 -ml-1 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                    fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                        clip-rule="evenodd" />
                </svg>
                {{ __('Back to List') }}
            </a>
        </div>

        <!-- Validation Errors -->
        @if ($errors->any())
            <div class="mb-8 rounded-lg bg-red-50 p-4 border border-red-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                            fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-red-800">
                            {{ __('There were errors with your submission') }}
                        </h3>
                        <div class="mt-2 text-sm text-red-700">
                            <ul role="list" class="list-disc pl-5 space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-8 sm:p-10">
                <form action="{{ route('palika.admin.store') }}" method="POST" enctype="multipart/form-data"
                    id="adminForm" class="space-y-8">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">

                        <!-- Name Input -->
                        <div class="col-span-1">
                            <label for="name" class="block text-sm font-semibold text-gray-700">
                                {{ __('Full Name') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg bg-gray-50 py-3 px-4 transition-colors">
                            </div>
                        </div>

                        <!-- Designation Select -->
                        <div class="col-span-1">
                            <label for="designation_id" class="block text-sm font-semibold text-gray-700">
                                {{ __('Designation') }} <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2">
                                <select id="designation_id" name="designation_id" required
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg bg-gray-50 py-3 px-4 transition-colors">
                                    <option value="" disabled {{ old('designation_id') ? '' : 'selected' }}>
                                        {{ __('Select a Designation') }}</option>
                                    @foreach ($designations as $designation)
                                        @php
                                            $isUsed = in_array($designation->id, $usedDesignations);
                                        @endphp
                                        <option value="{{ $designation->id }}" {{ $isUsed ? 'disabled' : '' }}
                                            {{ old('designation_id') == $designation->id ? 'selected' : '' }}
                                            class="{{ $isUsed ? 'text-gray-400 bg-gray-100' : 'text-gray-900' }}">
                                            {{ $designation->name }} @if ($isUsed)
                                                - {{ __('Already Assigned') }}
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-2 text-xs text-gray-500">
                                {{ __('Each designation can only be assigned to one active admin.') }}
                            </p>
                        </div>

                        <!-- Email Input -->
                        <div class="col-span-1">
                            <label for="email" class="block text-sm font-semibold text-gray-700">
                                {{ __('Email Address') }}
                            </label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path
                                            d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-lg bg-gray-50 py-3 transition-colors"
                                    placeholder="admin@example.com">
                            </div>
                        </div>

                        <!-- Phone Input -->
                        <div class="col-span-1">
                            <label for="phone" class="block text-sm font-semibold text-gray-700">
                                {{ __('Phone Number') }}
                            </label>
                            <div class="mt-2">
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-lg bg-gray-50 py-3 px-4 transition-colors"
                                    placeholder="+977 98XXXXXXXX">
                            </div>
                        </div>

                        <!-- Photo Upload -->
                        <div class="col-span-1 md:col-span-2 mt-2">
                            <span class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ __('Profile Photo') }} <span class="text-red-500">*</span>
                            </span>

                            <div class="mt-4 flex items-center gap-x-8">
                                <!-- Circular Preview Area -->
                                <label for="photo"
                                    class="h-24 w-24 sm:h-28 sm:w-28 rounded-full border-2 border-dotted border-gray-300 flex items-center justify-center overflow-hidden bg-white relative shrink-0 cursor-pointer hover:border-gray-400 transition-colors">
                                    <!-- Default User Icon -->
                                    <svg id="upload-icon" class="h-10 w-10 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <!-- Image Preview -->
                                    <img id="image-preview" src="#" alt="Preview"
                                        class="hidden absolute inset-0 h-full w-full object-cover">
                                </label>

                                <!-- Button Controls -->
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <label for="photo"
                                        class="cursor-pointer inline-flex items-center justify-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg class="mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                        </svg>
                                        {{ __('Upload photo') }}
                                        <input id="photo" name="photo" type="file" class="sr-only"
                                            accept="image/*" required
                                            onchange="
                                            const file = this.files[0];
                                            if (file) {
                                                const reader = new FileReader();
                                                reader.onload = function(e) {
                                                    const img = document.getElementById('image-preview');
                                                    img.src = e.target.result;
                                                    img.classList.remove('hidden');
                                                    document.getElementById('upload-icon').classList.add('hidden');
                                                }
                                                reader.readAsDataURL(file);
                                            }
                                        ">
                                    </label>

                                    <button type="button"
                                        onclick="
                                        document.getElementById('photo').value = '';
                                        document.getElementById('image-preview').classList.add('hidden');
                                        document.getElementById('image-preview').src = '#';
                                        document.getElementById('upload-icon').classList.remove('hidden');
                                    "
                                        class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 rounded-lg shadow-sm text-sm font-medium text-red-500 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-4">
                                PNG, JPG, GIF up to 2MB
                            </p>
                        </div>

                    </div>

                    <!-- Divider -->
                    <div class="hidden sm:block">
                        <div class="py-5">
                            <div class="border-t border-gray-200"></div>
                        </div>
                    </div>

                    <!-- Submit action -->
                    <div class="flex justify-end pt-5 sm:pt-0">
                        <button type="button" onclick="window.history.back()"
                            class="bg-white py-3 px-6 border border-gray-300 rounded-lg shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-3 px-8 border border-transparent shadow-sm text-sm font-bold rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            {{ __('Save Admin Profile') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
