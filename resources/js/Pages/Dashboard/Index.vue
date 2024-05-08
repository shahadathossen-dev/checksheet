<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import JetLabel from "@/Components/Label.vue";
import JetTextInput from "@/Components/TextInput.vue";
import JetCheckBox from "@/Components/Checkbox.vue";
import Card from '@/Components/Card.vue';
import { onBeforeMount, ref } from 'vue';
import axios from 'axios';

const dailyChecksheet = ref({})
const weeklyChecksheet = ref({})
const monthlyChecksheet = ref({})
const additionalTasks = ref([])
const purchaseRequests = ref([])

onBeforeMount( async () => {
    const {data: {checksheets: {daily, weekly, monthly}, additionalTasksList, purchaseRequestsList}} = await axios.get(route('dashboard.details'))
    dailyChecksheet.value = daily[0];
    weeklyChecksheet.value = weekly[0];
    monthlyChecksheet.value = monthly[0];
    additionalTasks.value = additionalTasksList;
    purchaseRequests.value = purchaseRequestsList;
})


</script>

<template>
<AppLayout title="Dashboard">
    <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </template>

    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
        <div class="title p-2">
            Checksheets
        </div>
        <section class="checksheets flex gap-2">
            <card>
                <template #left-header>
                    Daily Checksheets
                </template>
                <template #right-header>
                    Due Date: {{ dailyChecksheet.dueDate }}
                </template>
                <template #body>
                    <form class="w-full flex items-center gap-5 block my-2" :action="route('api.task-items.update', item.id)" method="POST" v-for="(item, index) in dailyChecksheet.items" :key="item.id">
                        <div class="task-item flex-grow">
                            <jet-label class="w-full" :for="`Note-${index}`" :value="item.checksheetItem.title" :required="!!item.checksheetItem.required" />

                            <jet-text-input v-model="item.note" :id="`Note-${index}`" type="text" class="mt-1 block w-full" placeholder="Note" :required="item.required" />
                        </div>
                        <jet-label class="" :for="`Done-${index}`">
                            <jet-check-box v-model="item.done" :id="`Done-${index}`" :checked="!!item.done" />
                            <span class="px-2 align-middle">Done</span>
                        </jet-label>
                        <!-- <jet-input-error :message="form.errors.check_sheet_items" class="mt-2" /> -->
                    </form>
                </template>
            </card>
            <card>
                <template #left-header>
                    Weekly Checksheets
                </template>
                <template #right-header>
                    Due Date: {{ weeklyChecksheet.dueDate }}
                </template>
                <template #body>
                    <form class="w-full flex items-center gap-5 block my-2" :action="route('api.task-items.update', item.id)" method="POST" v-for="(item, index) in weeklyChecksheet.items" :key="item.id">
                        <div class="task-item flex-grow">
                            <jet-label class="w-full" :for="`Note-${index}`" :value="item.checksheetItem.title" :required="!!item.checksheetItem.required" />

                            <jet-text-input v-model="item.note" :id="`Note-${index}`" type="text" class="mt-1 block w-full" placeholder="Note" :required="item.required" />
                        </div>
                        <jet-label class="" :for="`Done-${index}`">
                            <jet-check-box v-model="item.done" :id="`Done-${index}`" :checked="!!item.done" />
                            <span class="px-2 align-middle">Done</span>
                        </jet-label>
                        <!-- <jet-input-error :message="form.errors.check_sheet_items" class="mt-2" /> -->
                    </form>
                </template>
            </card>
            <card>
                <template #left-header>
                    Monthly Checksheets
                </template>
                <template #right-header>
                    Due Date: {{ monthlyChecksheet.dueDate }}
                </template>
                <template #body>
                    <form class="w-full flex items-center gap-5 block my-2" :action="route('api.task-items.update', item.id)" method="POST" v-for="(item, index) in monthlyChecksheet.items" :key="item.id">
                        <div class="task-item flex-grow">
                            <jet-label class="w-full" :for="`Note-${index}`" :value="item.checksheetItem.title" :required="!!item.checksheetItem.required" />

                            <jet-text-input v-model="item.note" :id="`Note-${index}`" type="text" class="mt-1 block w-full" placeholder="Note" :required="item.required" />
                        </div>
                        <jet-label class="" :for="`Done-${index}`">
                            <jet-check-box v-model="item.done" :id="`Done-${index}`" :checked="!!item.done" />
                            <span class="px-2 align-middle">Done</span>
                        </jet-label>
                        <!-- <jet-input-error :message="form.errors.check_sheet_items" class="mt-2" /> -->
                    </form>
                </template>
            </card>
        </section>
    </div>
</AppLayout>
</template>
<style scoped lang="scss">
// .card {
//     @apply  w-full;
//     &_title {
//         @apply  p-2 flex w-full justify-between;

//     }

//     &_body {
//         @apply p-2;
//     }

//     &_footer {
//         @apply p-2;
//     }
// }
</style>
