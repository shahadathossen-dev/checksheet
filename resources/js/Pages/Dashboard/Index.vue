<template>
<AppLayout title="Dashboard">
    <section class="mb-4 bg-white overflow-hidden shadow-sm sm:rounded-lg p-2">
        <div class="title px-2">
            Checksheets
        </div>
        <div class="checksheets flex gap-4 p-2">
            <card :header="true" bodyClass="max-h-72 overflow-y-auto">
                <template #left-header>
                    <strong>Daily Checksheets</strong>
                </template>
                <template #right-header>
                    <span class="font-normal">
                        Due Date:
                        {{ dailyChecksheet.dueDate }}
                    </span>
                </template>
                <template #body>
                    <template v-if="dailyChecksheet.items?.length">
                        <form @submit.prevent="submit(item, route('api.task-items.update', item.id), 'dailyChecksheet')" class="w-full flex items-center gap-5 block my-2" :action="route('api.task-items.update', item.id)" method="POST" v-for="(item, index) in dailyChecksheet.items" :key="item.id">
                            <div class="task-item flex-grow">
                                <jet-label class="w-full" :for="`Note-${index}`" :value="item.checksheetItem.title" :required="!!item.checksheetItem.required" />

                                <jet-input v-model="item.note" :id="`Note-${index}`" type="text" class="mt-1 block w-full" placeholder="Note" :required="!!item.checksheetItem.required" />
                            </div>
                            <jet-label class="mt-5 min-w-16" :for="`Done-${index}`">
                                <jet-input type="submit" value="submit" class="hidden" />
                                <jet-check-box v-model="item.done" :id="`Done-${index}`" :checked="!!item.done" @change="($event) => $event.target.checked ? $event.target.previousSibling.click() : item.done = 0" />
                                <span class="px-2 align-middle">Done</span>
                            </jet-label>
                        </form>
                    </template>
                    <template v-else>
                        <div class="h-full w-full flex justify-center items-center">
                            <h2 class="p-2">No Data found</h2>
                        </div>
                    </template>
                </template>
            </card>
            <card :header="true" bodyClass="max-h-72 overflow-y-auto">
                <template #left-header>
                    <strong>Weekly Checksheets</strong>
                </template>
                <template #right-header>
                    <span class="font-normal">
                        Due Date:
                        {{ weeklyChecksheet.dueDate }}
                    </span>
                </template>
                <template #body>
                    <template v-if="weeklyChecksheet.items?.length">
                        <form @submit.prevent="submit(item, route('api.task-items.update', item.id), 'weeklyChecksheet')" class="w-full flex items-center gap-5 block my-2" :action="route('api.task-items.update', item.id)" method="POST" v-for="(item, index) in weeklyChecksheet.items" :key="item.id">
                            <div class="task-item flex-grow">
                                <jet-label class="w-full" :for="`Note-${index}`" :value="item.checksheetItem.title" :required="!!item.checksheetItem.required" />

                                <jet-text-input v-model="item.note" :id="`Note-${index}`" type="text" class="mt-1 block w-full" placeholder="Note" :required="!!item.checksheetItem.required" />
                            </div>
                            <jet-label class="mt-5 min-w-16" :for="`Done-${index}`">
                                <jet-input type="submit" value="submit" class="hidden" />
                                <jet-check-box v-model="item.done" :id="`Done-${index}`" :checked="!!item.done" @change="($event) => $event.target.checked ? $event.target.previousSibling.click() : item.done = 0" />
                                <span class="px-2 align-middle">Done</span>
                            </jet-label>
                        </form>
                    </template>
                    <template v-else>
                        <div class="h-full w-full flex justify-center items-center">
                            <h2 class="p-2">No Data found</h2>
                        </div>
                    </template>
                </template>
            </card>
            <card :header="true" bodyClass="max-h-72 overflow-y-auto">
                <template #left-header>
                    <strong>Monthly Checksheets</strong>
                </template>
                <template #right-header>
                    <span class="font-normal">
                        Due Date:
                        {{ monthlyChecksheet.dueDate }}
                    </span>
                </template>
                <template #body>
                    <template v-if="monthlyChecksheet.items?.length">
                        <form @submit.prevent="submit(item, route('api.task-items.update', item.id), 'monthlyChecksheet')" class="w-full flex items-center gap-5 block my-2" :action="route('api.task-items.update', item.id)" method="POST" v-for="(item, index) in monthlyChecksheet.items" :key="item.id">
                            <div class="task-item flex-grow">
                                <jet-label class="w-full" :for="`Note-${index}`" :value="item.checksheetItem.title" :required="!!item.checksheetItem.required" />

                                <jet-text-input v-model="item.note" :id="`Note-${index}`" type="text" class="mt-1 block w-full" placeholder="Note" :required="item.checksheetItem.required" />
                            </div>
                            <jet-label class="mt-5 min-w-16" :for="`Done-${index}`">
                                <jet-input type="submit" value="submit" class="hidden" />
                                <jet-check-box v-model="item.done" :id="`Done-${index}`" :checked="!!item.done" @change="($event) => $event.target.checked ? $event.target.previousSibling.click() : item.done = 0" />
                                <span class="px-2 align-middle">Done</span>
                            </jet-label>
                        </form>
                    </template>
                    <template v-else>
                        <div class="h-full w-full flex justify-center items-center">
                            <h2 class="p-2">No Data found</h2>
                        </div>
                    </template>
                </template>
            </card>
        </div>
    </section>
    <section class="additional-items flex gap-4">
        <div class="additional-tasks w-1/2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <card :header="true" bodyClass="max-h-72 overflow-auto">
                <template #left-header>
                    Additional Tasks
                </template>
                <template #body>
                    <template v-if="additionalTasks.length">
                        <form @submit.prevent="submit(item, route('api.additional-tasks.update', item.id), 'additionalTasks')" class="w-full flex items-center gap-5 block my-2" :action="route('api.additional-tasks.update', item.id)" method="POST" v-for="(item, index) in additionalTasks" :key="item.id">
                            <div class="task-item flex-grow">
                                <jet-label class="w-full" :for="`Note-${index}`" :value="item.title" :required="!!item.required" />

                                <jet-text-input v-model="item.note" :id="`Note-${index}`" type="text" class="mt-1 block w-full" placeholder="Note" :required="item.required" />
                            </div>
                            <jet-label class="mt-5 min-w-16" :for="`Done-${index}`">
                                <jet-input type="submit" value="submit" class="hidden" />
                                <jet-check-box v-model="item.done" :id="`Done-${index}`" :checked="!!item.done" @change="($event) => $event.target.checked ? $event.target.previousSibling.click() : item.done = 0" />
                                <span class="px-2 align-middle">Done</span>
                            </jet-label>
                        </form>
                    </template>
                    <template v-else>
                        <div class="h-full w-full flex justify-center items-center">
                            <h2 class="p-2">No Data found</h2>
                        </div>
                    </template>
                </template>
            </card>
        </div>
        <div class="purchase-requests w-1/2 bg-white overflow-hidden shadow-sm sm:rounded-lg p-4">
            <card :header="true" :footer="true" bodyClass="max-h-72 overflow-auto">
                <template #left-header>
                    Purchase Requests
                </template>
                <template #body>
                    <template v-if="purchaseRequests.length">
                        <div class="w-full flex items-center gap-5 block my-2" v-for="(item, index) in purchaseRequests" :key="item.id">
                            <div class="task-item flex-grow">
                                <jet-label class="w-full" :for="`Note-${index}`" :value="item.title" />
                            </div>
                            <jet-label class="mt-5 text-capitalize" :for="`Status-${index}`">
                                {{item.status}}
                            </jet-label>
                        </div>
                    </template>
                    <template v-else>
                        <div class="h-full w-full flex justify-center items-center">
                            <h2 class="p-2">No Data found</h2>
                        </div>
                    </template>
                </template>
                <template #footer>
                    <form class="w-full flex items-center gap-5 block" @submit.prevent="submit(form, route('api.purchase-requests.store'), 'purchaseRequests')" :action="route('api.purchase-requests.store')" method="POST">
                        <div class="task-item flex-grow">
                            <jet-text-input v-model="form.title" id="Note-PurchaseRequest" type="text" class="mt-1 block w-full" placeholder="Note" required />
                        </div>
                        <jet-label for="Save-PurchaseRequest" class="p-2 rounded-md btn btn-success text-white">
                            <span class="ti ti-save"></span>
                            &nbsp;
                            <jet-input id="Save-PurchaseRequest" type="submit" value="Add" class="" />
                        </jet-label>
                    </form>
                </template>
            </card>
        </div>
    </section>

</AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import JetLabel from "@/Components/Label.vue";
import JetTextInput from "@/Components/TextInput.vue";
import JetInput from "@/Components/Input.vue";
import JetCheckBox from "@/Components/Checkbox.vue";
import Card from '@/Components/Card.vue';
import { onBeforeMount, reactive, ref } from 'vue';
import axios from 'axios';
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const dailyChecksheet = ref({})
const weeklyChecksheet = ref({})
const monthlyChecksheet = ref({})
const additionalTasks = ref([])
const purchaseRequests = ref([])

onBeforeMount( async () => {
    const {data: {checksheets: {daily, weekly, monthly}, additionalTasksList, purchaseRequestsList}} = await axios.get(route('dashboard.details'))
    dailyChecksheet.value = daily?.length ? daily[0] : {};
    weeklyChecksheet.value = weekly?.length ? weekly[0] : {};
    monthlyChecksheet.value = monthly?.length ? monthly[0] : {};
    additionalTasks.value = additionalTasksList;
    purchaseRequests.value = purchaseRequestsList;
})

const form = reactive({
    note: null,
    done: null,
    title: null,
})

const toastify = (message, type = 'success') => toast(message, {
  "theme": "auto",
  "type": type,
  "dangerouslyHTMLString": true
})

const submit = async (payload, route, type) => {
    Object.assign(form, payload)

    try {
        const {data} = await axios.post(route, form);
        if(type == 'purchaseRequests')
        purchaseRequests.value.push(data.data)
        else if(type == 'additionalTasks')
        additionalTasks.value = additionalTasks.value.filter(item => item.id != payload.id)
        else if(type == 'dailyChecksheet')
        dailyChecksheet.value.items = dailyChecksheet.value?.items.filter(item => item.id != payload.id)
        else if(type == 'weeklyChecksheet')
        weeklyChecksheet.value.items = weeklyChecksheet.value?.items.filter(item => item.id != payload.id)
        else if(type == 'monthlyChecksheet')
        monthlyChecksheet.value.items = monthlyChecksheet.value?.items.filter(item => item.id != payload.id)

        toastify(data.message);
    } catch (error) {
        console.log(error);
    }
}


</script>

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
