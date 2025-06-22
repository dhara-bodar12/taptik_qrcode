<template>
  <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Claim Your Offer: {{ offer.type }}</h2>

    <form @submit.prevent="submitForm">
      <div v-for="(field, index) in offer.fields" :key="index" class="mb-4">
        <input type="hidden" name="user_id" v-model="formData.user_id" />
        <label class="block text-sm font-medium mb-1">{{ field.label }}</label>

        <!-- Textual Inputs -->
        <input v-if="['text','email','number','date','tel','url','password'].includes(field.type)"
               :type="field.type"
               :name="field.name"
               v-model="formData[field.name]"
               class="w-full border rounded px-3 py-2"
               :required="field.required" />

        <!-- Textarea -->
        <textarea v-else-if="field.type === 'textarea'"
                  :name="field.name"
                  v-model="formData[field.name]"
                  class="w-full border rounded px-3 py-2"
                  :required="field.required"></textarea>

        <!-- Radio & Checkbox -->
        <div v-else-if="['radio','checkbox'].includes(field.type)">
          <label v-for="(opt, optIdx) in field.options" :key="optIdx" class="inline-flex items-center mr-4">
            <input :type="field.type"
                   :name="field.type === 'checkbox' ? field.name + '[]' : field.name"
                   :value="opt.value || opt.label"
                   v-model="formData[field.name]"
                   class="mr-1" />
            {{ opt.label }}
          </label>
        </div>
      </div>

      <button type="submit"
              class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Submit
      </button>

      <p v-if="successMessage" class="text-green-600 mt-3">{{ successMessage }}</p>
    </form>
  </div>
</template>

<script>
export default {
  props: {
    offer: Object,
    submitUrl: String
  },
  data() {
    return {
      formData: {},
      successMessage: ''
    }
  },
  mounted() {
    // Initialize form data
    this.offer.fields.forEach(field => {
      this.formData[field.name] = field.type === 'checkbox' ? [] : ''
    });
  },
  methods: {
    async submitForm() {
      try {
        const response = await fetch(this.submitUrl, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          },
          body: JSON.stringify(this.formData)
        });

        const data = await response.json();

        if (response.ok) {
          this.successMessage = data.message || 'Submitted successfully!';
        } else {
          alert(data.message || 'Something went wrong.');
        }
      } catch (err) {
        alert('Failed to submit form.');
        console.error(err);
      }
    }
  }
}
</script>
