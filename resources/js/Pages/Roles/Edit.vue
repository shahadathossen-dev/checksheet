<template>
  <form-view
    @submitted="update('roles.update', role.id)"
    title="Edit Role"
    :breadcrumb="breadcrumb"
  >
    <template #form>
      <!-- Name -->
      <form-group class="border-b">
        <jet-label class="md:w-1/4 mt-2" for="name" value="Name" required />
        <div class="w-full mt-1">
          <jet-input
            v-model="form.name"
            id="name"
            type="text"
            class="w-full"
            autocomplete="name"
          />
          <jet-input-error :message="form.errors.name" class="mt-2" />
        </div>
      </form-group>

      <!-- Description -->
      <form-group class="">
        <jet-label class="md:w-1/4 mt-2" for="name" value="Description" />
        <div class="w-full mt-1">
          <jet-text-input
            v-model="form.description"
            id="name"
            type="text"
            class="w-full"
            autocomplete="description"
          />
          <jet-input-error :message="form.errors.description" class="mt-2" />
        </div>
      </form-group>

      <!-- Permissions -->
      <form-group class="">
        <jet-label class="md:w-1/4 mt-2" for="name" value="Permissions" />
        <!-- Permissions Row -->
        <div class="mt-1 w-full">
          <div class="mb-5">
            <button
              @click="selectAll"
              type="button"
              class="btn btn-success mr-2"
            >
              Select All
            </button>
            <button type="button" class="btn btn-danger" @click="unSelectAll">
              UnSelect All
            </button>
          </div>
          <div class="grid grid-cols-3 gap-4 w-full">
            <div v-for="row in permissions" :key="row.id">
              <label class="flex items-center font-bold cursor-pointer text-sm">
                <jet-checkbox
                  class="mr-2"
                  :value="row.id"
                  v-model:checked="rowItems"
                  @update:checked="onSelectRow(row)"
                />
                <span>{{ row.label }}</span>
              </label>

              <!-- Permissions Columns -->
              <div v-for="col in row.permissions" :key="col.id" class="mt-1">
                <label class="flex items-center cursor-pointer text-sm">
                  <jet-checkbox
                    class="mr-2"
                    :value="col.id"
                    v-model:checked="form.permissions"
                    @update:checked="onSelectCol(row)"
                  />
                  <span>{{ col.label }}</span>
                </label>
              </div>
            </div>
          </div>
        </div>
      </form-group>
    </template>

    <template #actions>
      <Link :href="route('roles.index')" class="xs:mr-4">Cancel</Link>
      <jet-button
        @click.prevent="update('roles.update', role.id, true)"
        class="px-6 xs:mr-2 my-2 xs:my-0"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
        >Update & Continue</jet-button
      >
      <jet-button
        class="px-6"
        :class="{ 'opacity-25': form.processing }"
        :disabled="form.processing"
        >Update</jet-button
      >
    </template>
  </form-view>
</template>

<script>
import FormView from "@/Views/FormView.vue";
import { Link } from "@inertiajs/inertia-vue3";
import JetInput from "@/Components/Input.vue";
import JetCheckbox from "@/Components/Checkbox.vue";
import JetInputError from "@/Components/InputError.vue";
import JetLabel from "@/Components/Label.vue";
import JetTextInput from "@/Components/TextInput.vue";
import JetButton from "@/Components/Button.vue";
import FormGroup from "@/Components/FormGroup.vue";

export default {
  props: {
    permissions: {
      type: Array,
      default: () => [],
    },
    role: Object,
  },

  components: {
    Link,
    FormView,
    JetInput,
    JetCheckbox,
    JetInputError,
    JetLabel,
    JetTextInput,
    FormGroup,
    JetButton,
  },
  data() {
    return {
      breadcrumb: [
        { label: "Home", route: this.route("dashboard") },
        { label: "Roles", route: this.route("roles.index") },
        {
          label: this.role.name,
          route: this.route("roles.show", this.role.id),
        },
        { label: "Edit", route: null },
      ],

      form: this.$inertia.form({
        name: this.role.name,
        description: this.role.description,
        permissions: this.role.permissions,
      }),

      rowItems: [],
    };
  },
  methods: {
    /**
     * Select All
     */
    selectAll() {
      const allId = [];
      for (const row of this.permissions) {
        this.rowItems.push(row.id);
        for (const col of row.permissions) {
          allId.push(col.id);
        }
      }
      this.form.permissions = allId;
    },
    /**
     * Handle Select Column
     */
    onSelectCol(row) {
      this.checkAllCol(row);
    },

    /**
     * UnSelect All
     */
    unSelectAll() {
      this.rowItems = [];
      this.form.permissions = [];
    },
    /**
     * Handle Select Row
     */
    onSelectRow(row) {
      if (this.rowItems.includes(row.id)) {
        row.permissions.map((col) => {
          if (!this.form.permissions.includes(col.id)) {
            this.form.permissions.push(col.id);
          }
        });
      } else {
        row.permissions.map((col) => {
          this.form.permissions = this.form.permissions.filter(
            (item) => item != col.id
          );
        });
      }
    },
    /**
     * Check all fields of each row
     */
    checkAllCol(row) {
      const rowIds = row.permissions.map((p) => p.id);
      if (rowIds.every((id) => this.form.permissions.includes(id))) {
        this.rowItems.push(row.id);
      } else {
        this.rowItems = this.rowItems.filter((item) => item !== row.id);
      }
    },
  },

  created() {
    this.permissions.forEach((row) => this.checkAllCol(row));
  },
};
</script>
