<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Head, router} from '@inertiajs/vue3';
import {onBeforeMount, ref} from "vue";
import {addDays, format, isAfter, isToday, subDays} from "date-fns";
import SelectButton from 'primevue/selectbutton';
import Button from "primevue/button"
import LineChart from "@/Components/LineChart.vue";

const {data, queryParams, pairs, intervals, labels} = defineProps({
    'data': {
        type: Array,
        required: true,
    },
    'queryParams': {
        type: Object,
        required: true,
    },
    'pairs': {
        type: Object,
        required: true,
    },
    'intervals': {
        type: Object,
        required: true,
    },
    'labels': {
        type: Array,
        required: true,
    },
})

const currentDate = ref(new Date(queryParams.date));
const currencyPair = ref('');
const view = ref('');
const today = new Date();

onBeforeMount(() => {
    currencyPair.value = pairs.data.find(pair => pair.value === queryParams.pair) ?? pairs.data[0];
    view.value = intervals.data.find(interval => interval.value === queryParams.interval) ?? intervals.data[0];
})

const isNextDisabled = () => {
        return isToday(currentDate.value) || isAfter(currentDate.value, today);
}
const refreshData = () => {
    router.visit(route('dashboard', {
        pair: currencyPair.value.value,
        interval: view.value.value,
        date: format(currentDate.value, 'yyyy-MM-dd')
    }), {
        preserveState: true,
        preserveScroll: true,
    });
}

const onViewChange = () => {
    refreshData();
}

const onPairChange = () => {
    refreshData();
}

const previousDay = () => {
    currentDate.value = subDays(currentDate.value, 1);
    refreshData();
};

const nextDay = () => {
    currentDate.value = addDays(currentDate.value, 1);
    refreshData();
};
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between">
                            <div class="flex gap-x-2">
                                <Button class="w-32" @click="previousDay()">
                                    <span class="pi pi-arrow-left"></span>
                                    Previous
                                </Button>
                                <Button :disabled="isNextDisabled()" class="w-32"
                                        @click="nextDay()">
                                    Next
                                    <span class="pi pi-arrow-right"></span>
                                </Button>
                                <div v-text="format(currentDate,'dd/MM/yy')" class="text-gray-700 ml-4 mt-2">
                                </div>


                            </div>
                            <div class="flex gap-x-2">

                                <SelectButton v-model="currencyPair" optionLabel="label" dataKey="value"
                                              @value-change="onPairChange"
                                              :options="pairs.data"/>
                                <SelectButton v-model="view" optionLabel="label" dataKey="value"
                                              @value-change="onViewChange"
                                              :options="intervals.data"/>
                            </div>
                        </div>

                        <LineChart v-if="data.length > 0" class="mt-5" :key="data" :labels="labels" :data="data"/>
                        <div v-else class="text-center mt-5 text-gray-600"> There is no data found for this date</div>

                    </div>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>
</template>
