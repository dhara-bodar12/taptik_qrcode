<template>
  <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Claim Your Offer: {{ offer.type }}</h2>

    <!-- Success Message -->
    <p v-if="successMessage"
       class="flex items-center gap-2 p-4 mt-4 text-green-800 bg-green-100 border border-green-300 rounded-lg shadow-sm transition-all duration-300">
      <svg class="w-5 h-5 flex-shrink-0 text-green-600" fill="none" stroke="currentColor" stroke-width="2"
           viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
      </svg>
      <span>{{ successMessage }}</span>
    </p>

    <p v-if="errorMessage"
       class="flex items-center gap-2 p-4 mt-4 text-red-800 bg-red-100 border border-red-300 rounded-lg shadow-sm transition-all duration-300">
      <svg class="w-5 h-5 flex-shrink-0 text-red-600" fill="none" stroke="currentColor" stroke-width="2"
           viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
      <span>{{ errorMessage }}</span>
    </p>


    <form @submit.prevent="submitForm" enctype="multipart/form-data">
      <input type="hidden" v-model="formData.user_id" />

      <div v-for="(field, index) in offer.fields" :key="index" class="mb-4">
        <label class="block text-sm font-medium mb-1">{{ field.label }}</label>

        <!-- Text-like inputs -->
        <input v-if="['text','email','number','date','tel','url','password'].includes(field.type)"
               :type="field.type"
               v-model="formData[field.name]"
               :required="field.required"
               class="w-full border rounded px-3 py-2" />

        <!-- Textarea -->
        <textarea v-else-if="field.type === 'textarea'"
                  v-model="formData[field.name]"
                  :required="field.required"
                  class="w-full border rounded px-3 py-2"></textarea>

        <!-- File/Image Upload -->
        <input v-else-if="['file','image'].includes(field.type)"
               type="file"
               :ref="field.name"
               class="w-full border rounded px-3 py-2"
               :required="field.required"
               @change="handleFileChange($event, field.name)" />

        <!-- Radio & Checkbox -->
        <div v-else-if="['radio','checkbox'].includes(field.type)">
          <label v-for="(opt, i) in field.options || []" :key="i" class="inline-flex items-center mr-4">
            <input :type="field.type"
                   :name="field.name"
                   :value="opt.value || opt.label"
                   v-model="formData[field.name]"
                   class="mr-1" />
            {{ opt.label }}
          </label>
        </div>

        <!-- Select dropdown -->
        <select v-else-if="field.type === 'select'"
                v-model="formData[field.name]"
                class="w-full border rounded px-3 py-2"
                :required="field.required">
          <option disabled value="">-- Select --</option>
          <option v-for="(opt, i) in field.options || []" :key="i" :value="opt.value || opt.label">
            {{ opt.label }}
          </option>
        </select>

        <!-- Fallback -->
        <p v-else class="text-sm text-gray-500">Unsupported field type: {{ field.type }}</p>

        <!-- Errors -->
        <p v-if="errors[field.name]" class="text-red-600 text-sm mt-1">{{ errors[field.name][0] }}</p>
      </div>

      <button type="submit"
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Submit
      </button>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    offer: Object,
    submitUrl: String
  },
  data() {
    return {
      formData: {},
      errors: {},
      successMessage: '',
      errorMessage: '',
      files: {}
    };
  },
  mounted() {
    this.formData.user_id = this.offer.user_id || '';
    this.offer.fields.forEach(field => {
      this.formData[field.name] = field.type === 'checkbox' ? [] : '';
    });
  },
  methods: {
    handleFileChange(event, fieldName) {
      this.files[fieldName] = event.target.files[0];
    },
    async submitForm() {
      const form = new FormData();
      form.append('user_id', this.formData.user_id);
      form.append('offer_id', this.offer.id);

      for (const field of this.offer.fields) {
        const name = field.name;
        const value = this.formData[name];

        if (['file', 'image'].includes(field.type) && this.$refs[name]?.files.length > 0) {
          form.append(`submitted_data[${name}]`, this.$refs[name].files[0]);
        } else if (Array.isArray(value)) {
          value.forEach(v => form.append(`submitted_data[${name}][]`, v));
        } else {
          form.append(`submitted_data[${name}]`, value ?? '');
        }
      }

      try {
        const res = await axios.post(this.submitUrl, form, {
          headers: {
            'Content-Type': 'multipart/form-data'
          }
        });

        this.successMessage = res.data.message;
        this.errors = {};
      } catch (error) {
        if (error.response?.data?.message) {
          this.errorMessage = error.response.data.message;
        } else {
          this.errorMessage = 'Something went wrong. Please try again.';
        }
        this.successMessage = '';
      }
    }
  }
};
</script>
