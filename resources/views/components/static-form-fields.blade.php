<div class="grid gap-6 grid-cols-1 md:grid-cols-2">
    <!-- Householder Name -->
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white">
        <label class="block text-base font-semibold text-gray-900">
            घरमूलीको नाम <span class="required-asterisk">*</span>
        </label>
        <input type="text" x-model="formData.householder.householder_name"
            @input="clearHouseholderError('householder_name')" required
            class="input-field mt-4 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="घरमूलीको नाम राख्नुहोस्">
        <p x-show="householderErrors.householder_name" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.householder_name"></p>
    </div>

    <!-- Father Name -->
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white">
        <label class="block text-base font-semibold text-gray-900">
            बुबाको नाम <span class="required-asterisk">*</span>
        </label>
        <input type="text" x-model="formData.householder.father_name" @input="clearHouseholderError('father_name')"
            required
            class="input-field mt-4 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="बुबाको नाम राख्नुहोस्">
        <p x-show="householderErrors.father_name" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.father_name"></p>
    </div>

    <!-- Mother Name -->
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white">
        <label class="block text-base font-semibold text-gray-900">
            आमाको नाम<span class="required-asterisk">*</span>
        </label>
        <input type="text" x-model="formData.householder.mother_name" @input="clearHouseholderError('mother_name')"
            required
            class="input-field mt-4 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="आमाको नाम राख्नुहोस्">
        <p x-show="householderErrors.mother_name" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.mother_name"></p>
    </div>

    <!-- Mother Tongue -->
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white">
        <label class="block text-base font-semibold text-gray-900">
            मातृभाषा <span class="required-asterisk">*</span>
        </label>
        <select x-model="formData.householder.mother_tongue_id" @change="clearHouseholderError('mother_tongue_id')"
            required
            class="input-field mt-4 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">-- मातृभाषा छान्नुहोस् --</option>
            <template x-for="mt in lookupData.mother_tongues" :key="mt.id">
                <option :value="mt.id" x-text="mt.name"></option>
            </template>
        </select>
        <p x-show="householderErrors.mother_tongue_id" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.mother_tongue_id"></p>
    </div>

    <!-- Caste -->
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white">
        <label class="block text-base font-semibold text-gray-900">
            जाती <span class="required-asterisk">*</span>
        </label>
        <select x-model="formData.householder.caste_id" @change="clearHouseholderError('caste_id')" required
            class="input-field mt-4 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">-- जाती छान्नुहोस् --</option>
            <template x-for="caste in lookupData.castes" :key="caste.id">
                <option :value="caste.id" x-text="caste.name"></option>
            </template>
        </select>
        <p x-show="householderErrors.caste_id" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.caste_id"></p>
    </div>

    <!-- Tole -->
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white">
        <label class="block text-base font-semibold text-gray-900">
            टोल <span class="required-asterisk">*</span>
        </label>
        <select x-model="formData.householder.tole_id" @change="clearHouseholderError('tole_id')" required
            class="input-field mt-4 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">-- टोल छान्नुहोस् --</option>
            <template x-for="tole in lookupData.toles" :key="tole.id">
                <option :value="tole.id" x-text="tole.name"></option>
            </template>
        </select>
        <p x-show="householderErrors.tole_id" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.tole_id"></p>
    </div>

    <!-- Ward No -->
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white">
        <label class="block text-base font-semibold text-gray-900">
            वडा नं <span class="required-asterisk">*</span>
        </label>
        <input type="number" x-model.number="formData.householder.ward_no" @input="clearHouseholderError('ward_no')"
            required
            class="input-field mt-4 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="Enter ward number">
        <p x-show="householderErrors.ward_no" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.ward_no"></p>
    </div>

    <!-- House Number -->
    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white">
        <label class="block text-base font-semibold text-gray-900">
            घर नं <span class="required-asterisk">*</span>
        </label>
        <input type="text" x-model="formData.householder.house_number" @input="clearHouseholderError('house_number')"
            required
            class="input-field mt-4 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="घर नं राख्नुहोस्">
        <p x-show="householderErrors.house_number" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.house_number"></p>
    </div>

    <div class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white">
        <label class="block text-base font-semibold text-gray-900">
            घर बनेको जमिन वा घडेरीको कित्ता नं <span class="required-asterisk">*</span>
        </label>
        <input type="text" x-model="formData.householder.lot_number" @input="clearHouseholderError('lot_number')"
            required
            class="input-field mt-4 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="घडेरीको कित्ता नं राख्नुहोस्">
        <p x-show="householderErrors.lot_number" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.lot_number"></p>
    </div>

    <!-- Phone Number -->
    <div
        class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white">
        <label class="block text-base font-semibold text-gray-900">
            फोन नं <span class="required-asterisk">*</span>
        </label>
        <input type="tel" x-model="formData.householder.phone_number"
            @input="clearHouseholderError('phone_number')" required maxlength="10" pattern="[0-9]{10}"
            class="input-field mt-4 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500"
            placeholder="फोन नं राख्नुहोस्">
        <p x-show="householderErrors.phone_number" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.phone_number"></p>
    </div>

    <!-- Citizenship Permanent Address -->
    <div
        class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white md:col-span-2">

        <label class="block text-base font-semibold text-gray-900">
            नागरिकता स्थायी ठेगाना <span class="required-asterisk">*</span>
        </label>

        <select x-model="formData.householder.citizenship_permanent_address_id"
            @change="clearHouseholderError('citizenship_permanent_address_id')" required
            class="input-field mt-4 block w-full rounded-lg border border-gray-300 px-4 py-3 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <option value="">-- चयन गर्नुहोस् --</option>
            <template x-for="address in lookupData.citizenship_permanent_addresses" :key="address.id">
                <option :value="address.id" x-text="address.name"></option>
            </template>
        </select>

        <p x-show="householderErrors.citizenship_permanent_address_id" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.citizenship_permanent_address_id">
        </p>

    </div>

    <!-- Profile Photo -->
    <div
        class="rounded-xl border border-gray-200 bg-gray-50 p-6 transition-all hover:border-indigo-200 hover:bg-white md:col-span-2">
        <label class="block text-base font-semibold text-gray-900">
            घरमुलीको फोटो
        </label>
        <div class="file-upload-area mt-4 rounded-lg p-6 text-center"
            @drop.prevent="handleHouseholderFileDrop($event)"
            @dragover.prevent="$event.currentTarget.classList.add('drag-over')"
            @dragleave.prevent="$event.currentTarget.classList.remove('drag-over')">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <div class="mt-4">
                <label for="profile-photo-input" class="cursor-pointer">
                    <span class="text-indigo-600 font-medium hover:text-indigo-500">Upload
                        a photo</span>
                    <span class="text-gray-500"> or drag and
                        drop</span>
                </label>
                <input id="profile-photo-input" type="file" accept="image/*"
                    @change="handleHouseholderFileSelect($event)" class="hidden">
                <p class="mt-2 text-xs text-gray-500">PNG, JPG, JPEG up to 2MB</p>
            </div>
            <div x-show="formData.householder.profile_photo" class="mt-4">
                <div class="flex items-center justify-center gap-2">
                    <img :src="formData.householder.profile_photo_preview" alt="Profile preview"
                        class="h-20 w-20 rounded-lg object-cover">
                    <button type="button" @click="removeHouseholderPhoto()" class="text-red-600 hover:text-red-700">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <p x-show="householderErrors.profile_photo" class="mt-2 text-sm text-red-600 error-message"
            x-text="householderErrors.profile_photo"></p>
    </div>
</div>
