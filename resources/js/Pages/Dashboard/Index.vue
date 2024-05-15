<template>
<AppLayout title="Dashboard">
    <section class="mb-4 bg-white overflow-hidden shadow-sm sm:rounded-lg p-2">
        <div class="title px-2">
            Checksheets
        </div>
        <div class="checksheets flex flex-wrap lg:flex-nowrap gap-4 p-2">
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
                        <form @submit.prevent="submit(item, route('api.task-items.update', item.id))" class="max-w-full flex items-center gap-5 block my-2" v-for="(item, index) in dailyChecksheet.items" :key="item.id">
                            <div class="task-item flex-grow">
                                <jet-label class="w-full" :class="{'line-through': item.done == 1}" :for="`dailyTaskItemNote-${item.id}`" :value="item.title" :required="!!item.noteRequired" />

                                <jet-input v-model="item.note" :id="`dailyTaskItemNote-${item.id}`" type="text" class="mt-'done' block w-full" placeholder="Note" :required="!!item.noteRequired" :disabled="dailyChecksheet.status != 'pending'" />
                            </div>
                            <jet-label class="mt-5 min-w-16" :for="`dailyTaskItemDone-${item.id}`">
                                <jet-input type="submit" value="submit" class="hidden" />
                                <jet-check-box value="1" :checked="item.done == 1" v-model="item.done" :id="`dailyTaskItemDone-${item.id}`" @change="($event) => $event.target.previousSibling.click()" :disabled="dailyChecksheet.status != 'pending'" />
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
                        <form @submit.prevent="submit(item, route('api.task-items.update', item.id))" class="w-full flex items-center gap-5 block my-2" v-for="(item, index) in weeklyChecksheet.items" :key="item.id">
                            <div class="task-item flex-grow">
                                <jet-label class="w-full" :class="{'line-through': item.done == 1}" :for="`weeklyTaskItemNote-${index}`" :value="item.title" :required="!!item.noteRequired" />

                                <jet-text-input v-model="item.note" :id="`weeklyTaskItemNote-${index}`" type="text" class="mt-1 block w-full" placeholder="Note" :required="!!item.noteRequired" :disabled="weeklyChecksheet.status != 'pending'" />
                            </div>
                            <jet-label class="mt-5 min-w-16" :for="`weeklyTaskItemDone-${index}`">
                                <jet-input type="submit" value="submit" class="hidden" />
                                <jet-check-box value="1" :checked="item.done == 1" v-model="item.done" :id="`weeklyTaskItemDone-${index}`" @change="($event) => $event.target.previousSibling.click()" :disabled="weeklyChecksheet.status != 'pending'" />
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
                        <form @submit.prevent="submit(item, route('api.task-items.update', item.id))" class="w-full flex items-center gap-5 block my-2" v-for="(item, index) in monthlyChecksheet.items" :key="item.id">
                            <div class="task-item flex-grow">
                                <jet-label class="w-full" :class="{'line-through': item.done == 1}" :for="`monthlyTaskItemNote-${index}`" :value="item.title" :required="!!item.noteRequired" />

                                <jet-text-input v-model="item.note" :id="`monthlyTaskItemNote-${index}`" type="text" class="mt-1 block w-full" placeholder="Note" :required="item.noteRequired" :disabled="monthlyChecksheet.status != 'pending'" />
                            </div>
                            <jet-label class="mt-5 min-w-16" :for="`monthlyTaskItemDone-${index}`">
                                <jet-input type="submit" value="submit" class="hidden" />
                                <jet-check-box value="1" :checked="item.done == 1" v-model="item.done" :id="`monthlyTaskItemDone-${index}`" @change="($event) => $event.target.previousSibling.click()" :disabled="monthlyChecksheet.status != 'pending'" />
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
                        <form @submit.prevent="submit(item, route('api.additional-tasks.update', item.id))" class="w-full flex items-center gap-5 block my-2" v-for="(item, index) in additionalTasks" :key="item.id">
                            <div class="task-item flex-grow flex">
                                <jet-label class="flex-grow" :class="{'line-through': item.status == 'done'}" :for="`additionalTaskNote-${index}`" :value="item.title" />
                                <jet-label class="" v-if="item.dueDate" :for="`additionalTaskDueDate-${index}`" :value="item.dueDate" />

                            </div>
                            <jet-label class="min-w-16" :for="`additionalTaskDone-${index}`">
                                <jet-input type="submit" value="submit" class="hidden" />
                                <jet-check-box value="done" :checked="item.status == 'done'" v-model="item.status" :id="`additionalTaskDone-${index}`" @change="($event) => $event.target.previousSibling.click()" :disabled="item.status != 'pending'" />
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
                        <form @submit.prevent="submit(item, route('api.purchase-requests.update', item.id))" class="w-full flex items-center gap-5 block my-2 group" v-for="(item, index) in purchaseRequests" :key="item.id">
                            <div class="task-item flex-grow">
                                <jet-text-input v-if="item.edit" type="text" class="block w-full" placeholder="Title" required :for="`PurchaseNote-${index}`" v-model="item.title" :disabled="!item.edit" />
                                <jet-label v-else class="flex-grow" :class="{'line-through': item.status == 'done'}" :for="`PurchaseNote-${index}`" :value="item.title" />
                            </div>
                            <div v-if="item.status == 'pending'" class="invisible group-hover:visible flex gap-1">
                                <button v-if="item.edit" id="Update-PurchaseRequest" type="submit">
                                    <i class="ti ti-check"></i>
                                </button>
                                <button v-else id="Edit-PurchaseRequest" @click.prevent="(item.edit = true), (item.proxyTitle = item.title)" type="reset">
								    <i class="ti-pencil-alt"></i>
                                </button>
                                
                                <button v-if="item.edit" @click.prevent="(item.edit = false), (item.title = item.proxyTitle)" id="Reset-PurchaseRequest" type="reset">
								    <i class="ti-close"></i>
                                </button>

                                <button v-else @click.prevent="removeItem(item, route('api.purchase-requests.update', item.id))" id="Delete-PurchaseRequest" type="reset">
								    <i class="ti-trash"></i>
                                </button>
                            </div>
                            <jet-label class="capitalize" :for="`PurchaseStatus-${index}`">
                                {{item.status}}
                            </jet-label>
                        </form>
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
                            <jet-text-input v-model="form.title" id="Note-PurchaseRequest" type="text" class="mt-1 block w-full" placeholder="Title" required />
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
    status: null,
})

const toastify = (message, type = 'success') => toast(message, {
  "theme": "auto",
  "type": type,
  "dangerouslyHTMLString": true
})

const submit = async (payload, route, type = '') => {
    try {
        if(type == 'purchaseRequests') {
            const {data} = await axios.post(route, payload);
            form.title = null
            purchaseRequests.value.push(data.data)
        }
        
        const {data} = await axios.put(route, payload);

        toastify(data.message);
    } catch (error) {
        console.log(error);
    }
}

const removeItem = async (payload, route) => {
    if(payload.status == 'done') {
        toastify('Request already closed', 'error');
        return;
    }

    try {
        const {data} = await axios.delete(route);
        purchaseRequests.value = purchaseRequests.value.filter(item => item.id != payload.id)

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
